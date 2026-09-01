// Minimal MCP stdio client to enumerate tools from @automattic/mcp-wordpress-remote
const { spawn } = require('child_process');

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
  if (!isNotification) {
    msg.id = nextId++;
  }
  child.stdin.write(JSON.stringify(msg) + '\n');
  return msg.id;
}

function call(method, params) {
  return new Promise((resolve, reject) => {
    const id = send(method, params, false);
    pending.set(id, { resolve, reject });
    setTimeout(() => {
      if (pending.has(id)) {
        pending.delete(id);
        reject(new Error('timeout: ' + method));
      }
    }, 60000);
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
      } else {
        // notification or unknown — ignore
      }
    } catch (e) {
      console.error('Non-JSON line:', line);
    }
  }
});

child.stderr.on('data', (d) => process.stderr.write('[server stderr] ' + d));
child.on('exit', (code) => console.error('[server exited]', code));

(async () => {
  try {
    const init = await call('initialize', {
      protocolVersion: '2024-11-05',
      capabilities: {},
      clientInfo: { name: 'probe', version: '0.1' },
    });
    console.log('=== INITIALIZE ===');
    console.log(JSON.stringify(init, null, 2));
    send('notifications/initialized', {}, true);

    const tools = await call('tools/list', {});
    console.log('=== TOOLS ===');
    console.log(JSON.stringify(tools, null, 2));
  } catch (e) {
    console.error('FAILED:', e.message);
  } finally {
    child.kill();
    process.exit(0);
  }
})();
