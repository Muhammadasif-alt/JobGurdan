// Generic MCP stdio client: pass tool name + JSON args, print result
const { spawn } = require('child_process');

const [, , toolName, argsJson] = process.argv;
if (!toolName) {
  console.error('Usage: node mcp_call.cjs <toolName> [jsonArgs]');
  process.exit(2);
}
let args = {};
if (argsJson) {
  try { args = JSON.parse(argsJson); }
  catch (e) { console.error('Bad JSON args:', e.message); process.exit(2); }
}

const env = {
  ...process.env,
  WP_API_URL: 'https://ryzeoutdoorcreations.com/wp-json/mcp/mcp-adapter-default-server',
  WP_API_USERNAME: 'team@getlocalleads.agency',
  WP_API_PASSWORD: 'pO3kobhcvT38XsgfiN2arc2u',
};

const child = spawn(
  process.platform === 'win32' ? 'npx.cmd' : 'npx',
  ['-y', '@automattic/mcp-wordpress-remote@latest'],
  { env, stdio: ['pipe', 'pipe', 'pipe'], shell: true }
);

let buf = '';
const pending = new Map();
let nextId = 1;

function send(method, params, isNotification = false) {
  const msg = { jsonrpc: '2.0', method };
  if (params !== undefined) msg.params = params;
  if (!isNotification) msg.id = nextId++;
  child.stdin.write(JSON.stringify(msg) + '\n');
  return msg.id;
}

function call(method, params, timeoutMs = 120000) {
  return new Promise((resolve, reject) => {
    const id = send(method, params, false);
    pending.set(id, { resolve, reject });
    setTimeout(() => {
      if (pending.has(id)) { pending.delete(id); reject(new Error('timeout: ' + method)); }
    }, timeoutMs);
  });
}

child.stdout.on('data', (chunk) => {
  buf += chunk.toString();
  let idx;
  while ((idx = buf.indexOf('\n')) !== -1) {
    const line = buf.slice(0, idx).trim();
    buf = buf.slice(idx + 1);
    if (!line) continue;
    try {
      const msg = JSON.parse(line);
      if (msg.id !== undefined && pending.has(msg.id)) {
        const { resolve, reject } = pending.get(msg.id);
        pending.delete(msg.id);
        if (msg.error) reject(new Error(JSON.stringify(msg.error)));
        else resolve(msg.result);
      }
    } catch {}
  }
});

child.stderr.on('data', () => {});
child.on('exit', () => {});

(async () => {
  try {
    await call('initialize', {
      protocolVersion: '2024-11-05',
      capabilities: {},
      clientInfo: { name: 'probe', version: '0.1' },
    });
    send('notifications/initialized', {}, true);

    const result = await call('tools/call', { name: toolName, arguments: args });
    console.log(JSON.stringify(result, null, 2));
  } catch (e) {
    console.error('FAILED:', e.message);
    process.exitCode = 1;
  } finally {
    child.kill();
    setTimeout(() => process.exit(process.exitCode || 0), 200);
  }
})();
