<?php

use App\Models\Blog;
use App\Models\Job;

/**
 * Each 2026 market guide exists to correct something specific in the source
 * draft. These assertions pin those corrections so a later edit cannot quietly
 * remove the reason the page was written.
 */
function guideContent(string $slug, string $seeder): string
{
    test()->seed($seeder);

    return Blog::where('slug', $slug)->value('content');
}

it('tells Australian taxi drivers the NSW authority was abolished and that no salary figure describes them', function () {
    // The permit every guide tells readers to apply for stopped existing in 2017.
    $content = guideContent('taxi-driver-jobs-in-australia', Database\Seeders\TaxiDriverJobsAustraliaBlogSeeder::class);

    expect($content)->toContain('1 November 2017')
        ->toContain('Driver ID')
        ->toContain('authorised service provider');

    // The occupation code repeated across careers material belongs to trains.
    expect($content)->toContain('ANZSCO 7313 is Train and Tram Drivers')
        ->toContain('731112')
        ->toContain('711134')
        ->toContain('711133');

    // Licence history is the requirement that differs most between states.
    expect($content)->toContain('at least 12 months in the preceding four years')
        ->toContain('at least six months')
        ->toContain('at least three years in total, including at least one continuous year')
        ->toContain('at least 20 years old');

    // Fees and costs that are actually published.
    expect($content)->toContain('98.00')
        ->toContain('99.00')
        ->toContain('no cost to apply');

    // No state requires permanent residency, which is what stops people applying.
    expect($content)->toContain('VEVO')
        ->toContain('or a valid working visa')
        ->toContain('No state was found to require citizenship or permanent residency');

    // The pay question has no clean answer, and the reason is the point.
    expect($content)->toContain('cannot be verified against any official Australian source')
        ->toContain('1,695.70')
        ->toContain('Most taxi drivers are not employees');

    // Bailment sits outside the Fair Work system.
    expect($content)->toContain('Voros v Alan Dick')
        ->toContain('Taxi Industry (Contract Drivers) Contract Determination 1984')
        ->toContain('the word "taxi" does not appear in it');

    // A shrinking occupation, set against national growth.
    expect($content)->toContain('50,800')
        ->toContain('1,400')
        ->toContain('13.7 per cent')
        ->toContain('no shortage');

    // Ride-share overtook taxis, measured rather than asserted.
    expect($content)->toContain('48 per cent')
        ->toContain('39 per cent')
        ->toContain('905 million');

    // The levy is charged to providers, not drivers.
    expect($content)->toContain('1.20 per passenger service transaction')
        ->toContain('1.32');

    // The poster advertises sponsorship the list does not support.
    expect($content)->toContain('does not appear on the Core Skills Occupation List');
});

it('separates the two QA occupation codes in the Canada QA tester guide', function () {
    // The draft treats QA as one job with one salary band. Job Bank splits it
    // across two NOC codes with different education and different wages.
    $content = guideContent('qa-tester-jobs-in-canada', Database\Seeders\QaTesterJobsCanadaBlogSeeder::class);

    expect($content)->toContain('22222')
        ->toContain('21222')
        // Prevailing wages, Job Bank, updated 19 November 2025.
        ->toContain('17.50')
        ->toContain('35.00')
        ->toContain('51.28')
        ->toContain('28.85')
        ->toContain('46.15')
        ->toContain('68.68')
        ->toContain('19 November 2025')
        // The low-barrier claim does not carry to the better-paid code.
        ->toContain('usually requires a university degree')
        // Demand is rated as varying, not uniformly strong.
        ->toContain('varying across Canada')
        ->toContain('85.8');
});

it('corrects the renamed visa and the occupation list errors in the Australia sponsorship guide', function () {
    // The draft calls 482 the TSS visa and treats one "Skilled Occupation List"
    // as governing everything, including the route to permanent residence.
    $content = guideContent('visa-sponsorship-jobs-in-australia', Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder::class);

    expect($content)->toContain('Skills in Demand')
        ->toContain('Core Skills Occupation List')
        ->toContain('Medium and Long-term Strategic Skills List')
        ->toContain('Regional Occupation List')
        // Home Affairs states the transition streams have no occupation list.
        ->toContain('do not have an occupation list')
        ->toContain('most recently held temporary skilled visa')
        // TSMIT is not the threshold that applies to a 482 nomination.
        ->toContain('Core Skills Income Threshold')
        ->toContain('annual market salary rate')
        ->toContain('legislative instrument')
        ->toContain('caveat')
        ->toContain('up to 4 years');

    // The draft's AUD salary bands are not attributable to an official source.
    expect($content)->not->toContain('70,000')
        ->not->toContain('110,000');
});

it('corrects the Saudization and minimum wage errors in the Saudi no experience guide', function () {
    // The draft sends foreign readers at the one job category being closed to
    // them, and presents a salary range as though it were a legal floor.
    $content = guideContent('no-experience-jobs-in-saudi-arabia', Database\Seeders\NoExperienceJobsSaudiBlogSeeder::class);

    expect($content)->toContain('60 per cent')
        ->toContain('19/01/2026')
        ->toContain('Retail Sales Representative')
        // No statutory minimum wage applies to expatriate private-sector workers.
        ->toContain('no statutory minimum wage for expatriate private-sector workers')
        ->toContain('SAR 4,000')
        ->toContain('SAR 3,000')
        // A wage floor exists only where a specific decision creates one.
        ->toContain('SAR 5,500')
        // "Kafala-based" describes the system before the 2021 reforms.
        ->toContain('14 March 2021')
        ->toContain('Domestic workers and farm workers')
        ->toContain('Wage Protection System')
        ->toContain('Hadaf');
});

it('corrects the below-minimum pay figures in the UK store assistant guide', function () {
    // The draft quotes an hourly range and an annual range that both sit below
    // the National Living Wage for a full-time adult employee.
    $content = guideContent('store-assistant-jobs-in-uk', Database\Seeders\StoreAssistantJobsUkBlogSeeder::class);

    expect($content)->toContain('12.71')
        ->toContain('23,132.20')
        ->toContain('24,784.50')
        ->toContain('26,436.80')
        // The age-related rates matter in an article aimed at first jobs.
        ->toContain('10.85')
        ->toContain('8.00')
        ->toContain('39,039')
        // Perks cannot be used to reach the minimum wage.
        ->toContain('cannot count towards the minimum wage')
        // Flexible working is a right to ask, not a feature of the rota.
        ->toContain('eight business reasons')
        ->toContain('6 April 2024');
});

it('corrects the unlawful pay band and the dead tax relief in the UK work from home guide', function () {
    // The quoted entry-level band is below the legal minimum for full-time work.
    $content = guideContent('work-from-home-jobs-in-uk', Database\Seeders\WorkFromHomeJobsUkBlogSeeder::class);

    expect($content)->toContain('12.71')
        ->toContain('23,132.20')
        ->toContain('24,784.50')
        ->toContain('26,436.80')
        ->toContain('1,416 hours')
        ->toContain('it is not low, it is unlawful');

    // The middle of the whole workforce, for scale.
    expect($content)->toContain('39,039')
        ->toContain('19.67');

    // Home working does not suspend the minimum wage, including piece rates.
    expect($content)->toContain('even if the supplier of the work tells them that they are self-employed')
        ->toContain('divide that average by 1.2')
        ->toContain('in writing before you start');

    // The trend is a plateau, not an acceleration.
    expect($content)->toContain('28 per cent of workers in Great Britain were hybrid workers')
        ->toContain('settled rather than surging');

    // The day-one right, and its limits.
    expect($content)->toContain('6 April 2024')
        ->toContain('two statutory requests in any 12-month period')
        ->toContain('within two months')
        ->toContain('must consult you before rejecting')
        ->toContain('eight business reasons')
        ->toContain('not a right to have');

    // The 2025 Act is law but this part of it is not yet in force.
    expect($content)->toContain('18 December 2025')
        ->toContain('That change is not in force')
        ->toContain('2027');

    // No right to work from home, and no right to disconnect.
    expect($content)->toContain('no statutory right to disconnect');

    // What the employer must actually provide.
    expect($content)->toContain('cannot be charged for this')
        ->toContain('5 million')
        ->toContain('2,500 for every day');

    // The tax relief guides still recommend is unavailable this year.
    expect($content)->toContain('6 April 2026 to 5 April 2027')
        ->toContain('claim if your contract merely lets you work from home');

    // Charging a work-seeker a fee is a criminal offence, and enforcement moved.
    expect($content)->toContain('section 6 of the Employment Agencies Act 1973')
        ->toContain('replaced by the Fair Work Agency on 7 April 2026')
        ->toContain('0300 123 2040');

    // The honest answer on sponsorship for a fully remote role.
    expect($content)->toContain('60,000 per illegal worker')
        ->toContain('poor candidate for sponsorship');
});

it('prices the retail associate job by percentile instead of the range every guide copies', function () {
    // "$12 to $18 an hour" starts below the 10th percentile and stops below
    // the 75th, so the spread is quoted in full rather than summarised.
    $content = guideContent('retail-associate-jobs-in-usa', Database\Seeders\RetailAssociateJobsUsaBlogSeeder::class);

    expect($content)->toContain('$13.08')
        ->toContain('$14.38')
        ->toContain('$17.03')
        ->toContain('$18.59')
        ->toContain('$23.02')
        ->toContain('below the bottom tenth of the occupation');

    // A diploma is a preference on the posting, not an entry requirement.
    expect($content)->toContain('no formal educational credential')
        ->toContain('Work experience required: none');

    // Five states legislate no floor of their own, which no draft mentions.
    expect($content)->toContain('no state minimum wage law at all')
        ->toContain('$18.40')
        ->toContain('$13.77');

    // Retail sales work cannot be paid against a tip credit.
    expect($content)->toContain('more than $30 a month in tips');

    // The promotion claim, priced and then bounded by how many posts exist.
    expect($content)->toContain('$23.33')
        ->toContain('1,121,800')
        ->toContain('3,897,860');

    // Flat occupation, enormous churn — both halves, not just the friendly one.
    expect($content)->toContain('550,600')
        ->toContain('no change at all from 2025 to 2035');

    // Cashier is a separate occupation at a lower median.
    expect($content)->toContain('$15.81');

    // Only what the retailers publish themselves, and the gaps where they
    // publish nothing at all.
    expect($content)->toContain('$15 to $24 an hour')
        ->toContain('more than $18.50')
        ->toContain('$15 minimum set in August 2020')
        ->toContain('publish no company-wide hourly figure at all');

    // The education benefit and the hours threshold behind health cover.
    expect($content)->toContain('100 per cent of tuition and books')
        ->toContain('Target sets eligibility at 25 hours a week')
        ->toContain('60-day measurement period');

    // "Hired within days" is not a claim any large retailer makes.
    expect($content)->toContain('respond to applicants within a week')
        ->toContain('expire after 90 days')
        ->toContain('Plan for weeks rather than days');
});

it('tells database administrators the AWS certification is gone and prices the job it is actually for', function () {
    // The draft recommends an exam that cannot be sat: AWS withdrew it and
    // named no successor.
    $content = guideContent('database-administrator-jobs-in-usa', Database\Seeders\DatabaseAdministratorJobsUsaBlogSeeder::class);

    expect($content)->toContain('29 April 2024')
        ->toContain('AWS has not named a replacement');

    // The circulated band covers the middle half only.
    expect($content)->toContain('$60,230')
        ->toContain('$79,610')
        ->toContain('$104,620')
        ->toContain('$135,460')
        ->toContain('$163,320');

    // The figure most sites quote belongs to the combined OOH group.
    expect($content)->toContain('$126,760')
        ->toContain('Database Administrators and Architects')
        ->toContain('$139,500')
        ->toContain('$204,000');

    // "Growing demand" measured, and set against its own sector.
    expect($content)->toContain('4 per cent')
        ->toContain('3.5 per cent')
        ->toContain('7.3 per cent')
        ->toContain('7,300');

    // Healthcare pays below the occupational mean, contrary to every draft.
    expect($content)->toContain('$107,630')
        ->toContain('$98,000')
        ->toContain('$110,090');

    // The regimes that actually bind a US database administrator.
    expect($content)->toContain('$26,625,000')
        ->toContain('PCI DSS is not a law')
        ->toContain('Article 3(2)');

    // Certification names that have changed, and one that never existed.
    expect($content)->toContain('Oracle AI Database Administration Certified Professional')
        ->toContain('no official PostgreSQL certification');

    // Remote share is unmeasured rather than invented.
    expect($content)->toContain('BLS publishes no telework rate');
});

it('shows medical assistants a pay range that does not stop below their own median', function () {
    // The circulated ceiling of $45,000 sits under the $45,690 median.
    $content = guideContent('medical-assistant-jobs-in-usa', Database\Seeders\MedicalAssistantJobsUsaBlogSeeder::class);

    expect($content)->toContain('$36,050')
        ->toContain('$45,690')
        ->toContain('$49,180')
        ->toContain('$59,310')
        ->toContain('earn more than the figure most guides give as their ceiling');

    // Outpatient care centres outpay hospitals, and the largest setting pays
    // least of the three.
    expect($content)->toContain('$53,130')
        ->toContain('$47,660')
        ->toContain('$45,060')
        ->toContain('55.3 per cent');

    // The growth figure the draft leaves as an adjective.
    expect($content)->toContain('13 per cent')
        ->toContain('3.5 per cent')
        ->toContain('109,700');

    // The step up, priced.
    expect($content)->toContain('$64,400')
        ->toContain('$97,550');

    // Certification is gated by the programme, not earned by time served.
    expect($content)->toContain('There is no work-experience route')
        ->toContain('CAAHEP')
        ->toContain('ABHES');

    // Scope of practice is delegated, and the states diverge sharply.
    expect($content)->toContain('no state licenses medical assistants')
        ->toContain('finger or heel stick')
        ->toContain('invasive or requires assessment');

    // Two things the draft never says.
    expect($content)->toContain('does not meet the H-1B specialty occupation standard')
        ->toContain('Basic Life Support');
});

it('gives UK teachers the statutory scales and the border the qualification does not cross', function () {
    // The circulated starting salary is below the statutory minimum.
    $content = guideContent('teaching-jobs-in-uk', Database\Seeders\TeachingJobsUkBlogSeeder::class);

    expect($content)->toContain('&pound;34,069')
        ->toContain('&pound;46,940')
        ->toContain('&pound;41,729')
        ->toContain('&pound;32,916')
        ->toContain('it is an impossible one')
        ->toContain('&pound;52,835')
        ->toContain('&pound;148,829');

    // NQT has not been the term since 2021, and induction doubled.
    expect($content)->toContain('1 September 2021')
        ->toContain('two years, not one')
        ->toContain('early career teacher entitlement');

    // QTS is not a UK-wide qualification.
    expect($content)->toContain('will not make you eligible to teach in Scottish schools')
        ->toContain('&pound;43,383')
        ->toContain('&pound;54,453')
        ->toContain('&pound;43,830')
        ->toContain('no M1');

    // A PGCE is not the qualification the law asks for.
    expect($content)->toContain('does not by itself confer QTS');

    // The contract terms and the two large exceptions to them.
    expect($content)->toContain('1265 hours')
        ->toContain('195 days')
        ->toContain('Academies and free schools set their own pay')
        ->toContain('after 12 weeks');

    // A DBS certificate is a snapshot, and the fee is about to change.
    expect($content)->toContain('no official expiry date')
        ->toContain('5 October 2026');

    // The pension, priced, and the 2026 recognition change.
    expect($content)->toContain('1/57th')
        ->toContain('28.68 per cent')
        ->toContain('9 September 2026')
        ->toContain('Ghana, India or Nigeria')
        ->toContain('iQTS');

    // Wales: its own scale, its own regulator, and no M1 either.
    expect($content)->toContain('&pound;33,731')
        ->toContain('&pound;46,595')
        ->toContain('Education Workforce Council')
        ->toContain('QTLS is not recognised');

    // The academy QTS freedom, and the date it ends.
    expect($content)->toContain("Children's Wellbeing and Schools Act 2026")
        ->toContain('1 September 2027');
});

it('tells carpenters that OSHA certifies nobody and that a quarter of the trade works for itself', function () {
    // The quoted band stops around the 57th percentile.
    $content = guideContent('carpenter-jobs-in-usa', Database\Seeders\CarpenterJobsUsaBlogSeeder::class);

    expect($content)->toContain('$40,410')
        ->toContain('$48,510')
        ->toContain('$60,580')
        ->toContain('$76,830')
        ->toContain('$99,910')
        ->toContain('$65,630')
        ->toContain('57th percentile');

    // The structural fact every carpenter guide omits.
    expect($content)->toContain('25 per cent of carpenters are self-employed')
        ->toContain('670,090')
        ->toContain('889,700')
        ->toContain('219,610');

    // OSHA does not certify workers, but several jurisdictions mandate the card.
    expect($content)->toContain('Outreach Training Program is considered a certification')
        ->toContain('within 15 days of being hired')
        ->toContain('Site Safety Training card showing at least 40 hours');

    // Licensing attaches to the business, not the carpenter.
    expect($content)->toContain('carpentry is not among them')
        ->toContain('C-5 Framing and Rough Carpentry')
        ->toContain('no dollar threshold at all');

    // Apprenticeship figures stated as typical, and the union claim unsourced.
    expect($content)->toContain('8,000')
        ->toContain('144 hours')
        ->toContain('publishes no wage figures');

    // The visa route the posters advertise is the wrong one.
    expect($content)->toContain('fails that test on its face')
        ->toContain('66,000 a year');

    // The risk the draft never mentions.
    expect($content)->toContain('7.5 per 100,000')
        ->toContain('51 of those 89 deaths')
        ->toContain('62,800');
});

it('keeps the UK day rate and hourly rate apart instead of averaging them', function () {
    // GBP 155 a day is self-employed turnover before the van; GBP 13.96 an
    // hour is employed pay with holiday and pension behind it.
    $content = guideContent('delivery-driver-jobs-in-uk', Database\Seeders\DeliveryDriverJobsUkBlogSeeder::class);

    expect($content)->toContain('&pound;155')
        ->toContain('&pound;13.96')
        ->toContain('self-employed turnover')
        ->toContain('&pound;12.71')
        ->toContain('&pound;24,784.50')
        ->toContain('&pound;22,596')
        ->toContain('&pound;2,188.50 below the legal minimum for an employee');

    // The percentage in the draft does not reconcile with its own average.
    expect($content)->toContain('&pound;183')
        ->toContain('18 per cent');

    // Worker status is a conclusion, not a label, and the clause decides it.
    expect($content)->toContain('Uber BV v Aslam')
        ->toContain('IWGB v Central Arbitration Committee')
        ->toContain('substitution clause');

    // The insurance the draft omits entirely.
    expect($content)->toContain('hire and reward')
        ->toContain('section 143 of the Road Traffic Act 1988');

    // Two separate gates, not one line about "additional licensing".
    expect($content)->toContain('1 January 1997')
        ->toContain('35 hours of periodic training every five years')
        ->toContain('is not describing a parcel van job');

    // And the listing itself has to say which of the two rates it is quoting.
    expect(Job::where('position', 'like', 'Delivery Driver%')->value('description'))
        ->toContain('That is turnover, not pay')
        ->toContain('substitution clause');
});

it('shows the Canadian entry-level figure cannot sit above the average', function () {
    $content = guideContent('customer-service-jobs-in-canada', Database\Seeders\CustomerServiceJobsCanadaBlogSeeder::class);

    expect($content)->toContain('$20.43')
        ->toContain('$49,142')
        ->toContain('$42,494')
        ->toContain('$6,648 higher than the overall average');

    // The measured Job Bank median, which is above the job board average.
    expect($content)->toContain('$22.00')
        ->toContain('$16.00')
        ->toContain('$33.14');

    // The quoted floor is already unlawful in British Columbia.
    expect($content)->toContain('$18.15 is below British Columbia')
        ->toContain('$18.25')
        ->toContain('$17.95')
        ->toContain('$15.00');

    // Bilingualism is a tested profile, and federal hiring has an order.
    expect($content)->toContain('Bilingual imperative')
        ->toContain('Second Language Evaluation')
        ->toContain('Public Service Employment Act')
        ->toContain('Canadian citizens and permanent residents');
});

it('prices the Australian average against the award it actually reflects', function () {
    $content = guideContent('construction-worker-jobs-in-australia', Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class);

    expect($content)->toContain('$71,059')
        ->toContain('$26.67')
        ->toContain('$52,700')
        ->toContain('$65,880')
        ->toContain('$26.44');

    // Superannuation is larger than the entire spread between the states.
    expect($content)->toContain('12 per cent')
        ->toContain('$7,613')
        ->toContain('$3,344');

    // The White Card is a legal gate with a disuse rule, not a listed skill.
    expect($content)->toContain('legal condition of entering a construction site')
        ->toContain('no construction work for two consecutive years')
        ->toContain('recognised across Australia');

    // Work rights, on a site whose readers are mostly outside Australia.
    expect($content)->toContain('88 days')
        ->toContain('179 days')
        ->toContain('not a skilled migration occupation');
});

it('gives the US data entry projection as a number rather than an adjective', function () {
    $content = guideContent('data-entry-jobs-in-usa', Database\Seeders\DataEntryJobsUsaBlogSeeder::class);

    expect($content)->toContain('25.9 per cent')
        ->toContain('2024 and 2034')
        ->toContain('$39,850')
        ->toContain('$19.16')
        ->toContain('$49,500');

    // The published tables sit above the federal median.
    expect($content)->toContain('below the bottom of that band');

    // Remote pays the median, contradicting the draft's premium claim.
    expect($content)->toContain('the median')
        ->toContain('not a premium');

    // What automation is not taking.
    expect($content)->toContain('Exception handling')
        ->toContain('transcription without judgement');
});

it('separates basic pay from take-home in the Pakistani government grades', function () {
    $content = guideContent('data-entry-jobs-in-pakistan', Database\Seeders\DataEntryJobsPakistanBlogSeeder::class);

    expect($content)->toContain('Revised Basic Pay Scales 2026')
        ->toContain('1 July 2026')
        ->toContain('not measuring the same thing');

    // The grade is the salary, and it is not fixed at one grade.
    expect($content)->toContain('BPS-11 to BPS-14');

    // The practical test is a separate gate from the written paper.
    expect($content)->toContain('pass or fail on its own');

    // An eightfold spread is not a band, and the bonus is worth naming.
    expect($content)->toContain('eightfold')
        ->toContain('PKR 700 to PKR 1,400 a month');
});

it('corrects the claim that a SIRA licence covers the whole UAE', function () {
    $content = guideContent('security-guard-jobs-in-uae', Database\Seeders\SecurityGuardJobsUaeBlogSeeder::class);

    expect($content)->toContain('Emirate of Dubai')
        ->toContain('Private Security Business Department')
        ->toContain('Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah')
        ->toContain("cannot work in the other's territory");

    // Gratuity is on basic salary alone, which reverses the draft's advice.
    expect($content)->toContain('Federal Decree-Law No. 33 of 2021')
        ->toContain('excluded from the calculation')
        ->toContain('40 per cent smaller');

    expect(Job::where('position', 'like', 'Security Guard%')->value('description'))
        ->toContain('SIRA for Dubai, PSBD for Abu Dhabi and the northern emirates');
});

it('anchors US retail pay on the state floor rather than a national average', function () {
    $content = guideContent('retail-jobs-in-usa', Database\Seeders\RetailJobsUsaBlogSeeder::class);

    // Five averages spanning 57 per cent are not five measurements.
    expect($content)->toContain('57 per cent apart')
        ->toContain('$12.72')
        ->toContain('$20.00');

    // BLS separates the two occupations the drafts merge.
    expect($content)->toContain('$17.03')
        ->toContain('$14.99')
        ->toContain('$2.04');

    // The number every retail guide leaves out.
    expect($content)->toContain('$7.25')
        ->toContain('24 July 2009')
        ->toContain('$18.40')
        ->toContain('higher than the national median wage for retail salespersons');

    // Scheduling cuts both ways and the advert will not say which.
    expect($content)->toContain('Flexible for you')
        ->toContain('Flexible for them');
});

it('shows the Australian office assistant guide contradicting its own average', function () {
    $content = guideContent('office-assistant-jobs-in-australia', Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class);

    expect($content)->toContain('$62,200')
        ->toContain('$59,000')
        ->toContain('$60,500')
        ->toContain('below $62,200');

    // One published floor is below the legal minimum for full-time work.
    expect($content)->toContain('$1,004.90')
        ->toContain('$52,255')
        ->toContain('$50,475')
        ->toContain('below the legal minimum');

    // Super is worth more than the spread between the four averages.
    expect($content)->toContain('$7,464');

    // The check that does not travel, against the police check that does.
    expect($content)->toContain('not transferable between them')
        ->toContain('Blue Card')
        ->toContain('A National Police Check is national');
});

it('measures German factory pay against the statutory minimum wage', function () {
    $content = guideContent('factory-worker-jobs-in-germany', Database\Seeders\FactoryWorkerJobsGermanyBlogSeeder::class);

    expect($content)->toContain('EUR 13.90')
        ->toContain('EUR 14.60')
        ->toContain('EUR 2,409');

    // Four of the five published range floors are below the legal minimum.
    expect($content)->toContain('EUR 1,959')
        ->toContain('EUR 2,177')
        ->toContain('EUR 2,216')
        ->toContain('EUR 2,383')
        ->toContain('All four are below EUR 13.90');

    // Gross here is not gross elsewhere.
    expect($content)->toContain('roughly a fifth of gross')
        ->toContain('Steuerklasse');

    // The Opportunity Card is a skilled worker instrument with an hours cap.
    expect($content)->toContain('20 hours a week')
        ->toContain('six points')
        ->toContain('minimum salary requirement');
});

it('says which of the two Canadian farm programs the reader can actually use', function () {
    $content = guideContent('farm-worker-jobs-in-canada', Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class);

    // SAWP runs on bilateral agreements, so the country list is the gate.
    expect($content)->toContain('Mexico')
        ->toContain('Trinidad and Tobago')
        ->toContain('SAWP is closed to you')
        ->toContain("your own country's labour ministry");

    // Canada has no sponsorship for work permits; it has an LMIA.
    expect($content)->toContain('no employer sponsorship for a work permit')
        ->toContain('Labour Market Impact Assessment')
        ->toContain('LMIA approved');

    // The quoted floor is below the minimum wage in the two biggest markets.
    expect($content)->toContain('$16 an hour is below the general minimum wage')
        ->toContain('$18.25')
        ->toContain('$17.95');

    // Housing is a program requirement, and the PR pathway is shut.
    expect($content)->toContain('inspected within the last eight months')
        ->toContain('Agri-Food Pilot')
        ->toContain('1,010')
        ->toContain('14 May 2025');
});

it('separates base salary from total compensation for data scientists', function () {
    $content = guideContent('data-scientist-jobs-in-usa', Database\Seeders\DataScientistJobsUsaBlogSeeder::class);

    // Junior and entry level are the same rung, reported far apart.
    expect($content)->toContain('$82,850')
        ->toContain('$104,847')
        ->toContain('$21,997 apart');

    // The measured median against the job board average.
    expect($content)->toContain('$112,590')
        ->toContain('$131,121')
        ->toContain('$18,531');

    // Salary and total compensation are different quantities.
    expect($content)->toContain('are not the same quantity')
        ->toContain('base plus annual bonus plus equity');

    // The growth figure the drafts bury, and the role that is not data science.
    expect($content)->toContain('34 per cent')
        ->toContain('23,400')
        ->toContain('is an accurate description of');
});

it('flags the wiring regulations amendment that lapses in October 2026', function () {
    $content = guideContent('electrician-jobs-in-uk', Database\Seeders\ElectricianJobsUkBlogSeeder::class);

    expect($content)->toContain('BS 7671:2018+A4:2026')
        ->toContain('15 April 2026')
        ->toContain('withdrawn on 15 October 2026')
        ->toContain('The 19th Edition does not exist');

    // The ECS card is contractual; Part P is the law.
    expect($content)->toContain('The ECS card is contractual')
        ->toContain('Part P of the Building Regulations is the law')
        ->toContain('scheme membership is not compulsory')
        ->toContain('calibrated test instruments');

    // Apprentice pay against the rate the apprentice is actually entitled to.
    expect($content)->toContain('&pound;8.00')
        ->toContain('&pound;15,600')
        ->toContain('&pound;24,784.50')
        ->toContain('&pound;2,784.50 short');

    // The deduction that comes off a day rate before it reaches you.
    expect($content)->toContain('Construction Industry Scheme')
        ->toContain('20 per cent')
        ->toContain('30 per cent')
        ->toContain('labour portion only');
});

it('separates a plumbing charge-out rate from a wage and maps where the licence travels', function () {
    $content = guideContent('plumber-jobs-in-australia', Database\Seeders\PlumberJobsAustraliaBlogSeeder::class);

    // A charge-out rate is turnover, and unbilled hours are the largest deduction.
    expect($content)->toContain('charge-out rate')
        ->toContain('Unbilled hours, which are the big one');

    // Apprentice pay below the national minimum wage is lawful under the award.
    expect($content)->toContain('$26.44')
        ->toContain('$22.77')
        ->toContain('percentages of the qualified tradesperson');

    // The licence travels under AMR, with two holes in it.
    expect($content)->toContain('Automatic Mutual Recognition')
        ->toContain('Queensland is outside the scheme, in both directions')
        ->toContain('Gasfitting classes are exempt from AMR');

    // Super, and the migration answer that is genuinely positive for a trade.
    expect($content)->toContain('$9,600')
        ->toContain('Plumbing is a skilled trade occupation')
        ->toContain('it does not license you');
});

it('shows why a UAE receptionist package split matters more than its total', function () {
    $content = guideContent('receptionist-jobs-in-uae', Database\Seeders\ReceptionistJobsUaeBlogSeeder::class);

    // Gratuity is on basic salary alone, so the split changes the entitlement.
    expect($content)->toContain('Federal Decree-Law No. 33 of 2021')
        ->toContain('basic salary only')
        ->toContain('AED 3,150')
        ->toContain('AED 1,890')
        ->toContain('AED 1,260 a year');

    // DIFC runs its own employment law and replaced gratuity with DEWS.
    expect($content)->toContain('DEWS')
        ->toContain('1 February 2020')
        ->toContain('5.83 per cent of basic salary')
        ->toContain('8.33 per cent');

    // The visa is the employer's cost, and a visit visa is not a work permit.
    expect($content)->toContain('directly or indirectly')
        ->toContain('Working on one is not');

    // The apply link goes to the UAE site rather than the American one.
    expect($content)->toContain('https://ae.indeed.com/q-receptionist-jobs.html')
        ->not->toContain('www.indeed.com/jobs?q=receptionist');
});

it('prices US gig delivery pay against engaged time and the cost of the car', function () {
    $content = guideContent('delivery-driver-jobs-in-usa', Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class);

    // The Proposition 22 guarantee counts engaged time only.
    expect($content)->toContain('Proposition 22')
        ->toContain('25 July 2024')
        ->toContain('120 per cent of the minimum wage')
        ->toContain('not engaged time');

    // The car is the largest cost, priced at the IRS mileage rate.
    expect($content)->toContain('72.5 cents a mile')
        ->toContain('76 cents a mile from 1 July 2026')
        ->toContain('costs about $76');

    // Three employment structures hiding behind one pay band.
    expect($content)->toContain('Delivery Service Partners')
        ->toContain('Teamsters')
        ->toContain('the name on the van is not the name on your paycheck');

    // Location sets the floor, and contractors have none federally.
    expect($content)->toContain('$22.13 an hour before tips')
        ->toContain('$7.25')
        ->toContain('no federal minimum wage at all')
        ->toContain('15.3 per cent');
});

it('tells Pakistani teachers which qualification exists and who really employs them', function () {
    $content = guideContent('teacher-jobs-in-pakistan', Database\Seeders\TeacherJobsPakistanBlogSeeder::class);

    // PTC and CT are gone; a new candidate qualifies through ADE or B.Ed (Hons).
    expect($content)->toContain('PTC and CT were discontinued')
        ->toContain('2011-12 academic session')
        ->toContain('Associate Degree in Education')
        ->toContain('B.Ed (Hons)');

    // The quoted private school floor is below every provincial minimum wage.
    expect($content)->toContain('PKR 25,000')
        ->toContain('PKR 40,700')
        ->toContain('PKR 43,000')
        ->toContain('PKR 45,000')
        ->toContain('below the minimum wage for an unskilled worker in every province');

    // A government school is not always a government job.
    expect($content)->toContain('Punjab Education Foundation')
        ->toContain('PEIMA')
        ->toContain('2,735')
        ->toContain('Sindh Education Foundation')
        ->toContain('PKR 10,000 to PKR 15,000')
        ->toContain('who actually employs you');

    // BPS figures need the basic-versus-gross reading from the data entry guide.
    expect($content)->toContain('Revised Basic Pay Scales 2026')
        ->toContain('/blog/data-entry-jobs-in-pakistan');
});

it('tells nurses in Saudi Arabia the exam is not the licence and which recruitment routes are legal', function () {
    $content = guideContent('nurse-jobs-in-saudi-arabia', Database\Seeders\NurseJobsSaudiBlogSeeder::class);

    // Prometric delivers one step; SCFHS classification and registration is the licence.
    expect($content)->toContain('The Prometric exam is not the licence')
        ->toContain('Mumaris Plus')
        ->toContain('DataFlow')
        ->toContain('verified qualifications and experience');

    // The quoted floor is the Saudization threshold, not a wage floor.
    expect($content)->toContain('SAR 4,000')
        ->toContain('no statutory minimum wage for expatriate workers')
        ->toContain('/blog/security-guard-jobs-in-saudi-arabia');

    // Indian nurses have two legal routes, both through eMigrate.
    expect($content)->toContain('eMigrate')
        ->toContain('Ministries of Health and of Defence and Aviation')
        ->toContain('NORKA Roots, ODEPC, OMCL, UPFC, OMCAP and TOMCOM');

    expect(Job::where('position', 'like', 'Staff Nurse%')->value('description'))
        ->toContain('SCFHS professional classification and registration')
        ->toContain('only through eMigrate');
});

it('prices UK IT support pay against the legal minimum and the sponsorship salary threshold', function () {
    $content = guideContent('it-support-jobs-in-uk', Database\Seeders\ItSupportJobsUkBlogSeeder::class);

    // Both published floors are below the National Living Wage on a full-time week.
    expect($content)->toContain('&pound;12.71')
        ->toContain('&pound;24,784.50')
        ->toContain('&pound;4,784.50 short')
        ->toContain('&pound;784.50 short');

    // Below RQF 6, kept open by the Temporary Shortage List, and priced out by the threshold.
    expect($content)->toContain('3132 IT user support technicians')
        ->toContain('3131 IT operations technicians')
        ->toContain('Table 1a')
        ->toContain('RQF level 6')
        ->toContain('Temporary Shortage List')
        ->toContain('before 31 December 2026')
        ->toContain('&pound;41,700')
        ->toContain('&pound;33,400')
        ->toContain('cannot meet the salary rules');

    // A+ is two exams, and clearance is a residence question.
    expect($content)->toContain('220-1201')
        ->toContain('220-1202')
        ->toContain('normally have been resident in the UK for the last five years');

    expect(Job::where('position', 'like', 'IT Support Technician%')->value('description'))
        ->toContain('Temporary Shortage List')
        ->toContain('&pound;41,700');
});

it('prices US cashier pay against the state floors the draft recommends', function () {
    $content = guideContent('cashier-jobs-in-usa', Database\Seeders\CashierJobsUsaBlogSeeder::class);

    // $12 is below the 2026 minimum wage in three of the five states named.
    expect($content)->toContain('three of the five')
        ->toContain('$16.90')
        ->toContain('$17.00')
        ->toContain('$16.00')
        ->toContain('$14.00')
        ->toContain('$15.00 from 30 September 2026')
        ->toContain('$7.25');

    // The measured median sits above the draft's experienced floor.
    expect($content)->toContain('$15.81 an hour in May 2025')
        ->toContain('$14.68')
        ->toContain('$16.87');

    // California's fast food rate, and the shortage rule every cashier meets.
    expect($content)->toContain('1 April 2024')
        ->toContain('at least 60 establishments')
        ->toContain('$20.00 an hour')
        ->toContain('cash or merchandise shortages');

    // Shrinking but hiring, and the hours rule for 14 and 15 year olds.
    expect($content)->toContain('decline 6 per cent from 2025 to 2035')
        ->toContain('521,300 openings every year')
        ->toContain('3 hours on a school day')
        ->toContain('/blog/retail-jobs-in-usa');

    expect(Job::where('position', 'like', 'Cashier%')->value('description'))
        ->toContain('below the minimum wage')
        ->toContain('Form I-9');
});

it('corrects UK cook pay, the hygiene certificate myth and the sponsorship promise', function () {
    $content = guideContent('cook-jobs-in-uk', Database\Seeders\CookJobsUkBlogSeeder::class);

    // Three of the four published floors are below the National Living Wage.
    expect($content)->toContain('&pound;12.71')
        ->toContain('&pound;24,784.50')
        ->toContain('&pound;6,784.50 short')
        ->toContain('&pound;4,784.50 short')
        ->toContain('&pound;29,741.40');

    // The certificate is evidence of training, not a legal requirement.
    expect($content)->toContain("food handlers don't have to hold a food hygiene certificate")
        ->toContain('supervised and instructed or trained in food hygiene')
        ->toContain("Natasha's Law");

    // The occupation codes decide sponsorship, and none of them is open.
    expect($content)->toContain('5435 Cooks')
        ->toContain('Table 6')
        ->toContain('5434 Chefs')
        ->toContain('not on the Temporary Shortage List')
        ->toContain('9263 Kitchen and catering assistants');

    // The tips law the draft leaves out.
    expect($content)->toContain('Employment (Allocation of Tips) Act 2023')
        ->toContain('1 October 2024');

    expect(Job::where('position', 'like', 'Cook%')->value('description'))
        ->toContain('cannot be sponsored');
});

it('tells UAE accountants who pays the visa, who may sign audits and which tax dates matter', function () {
    $content = guideContent('accountant-jobs-in-uae', Database\Seeders\AccountantJobsUaeBlogSeeder::class);

    // The visa is a legal cost of the employer, not a perk.
    expect($content)->toContain('Federal Decree-Law No. 33 of 2021')
        ->toContain('directly or indirectly')
        ->toContain('AED 15,000');

    // A qualification is not a licence to sign an audit or act as a tax agent.
    expect($content)->toContain('Federal Decree-Law No. 41 of 2023')
        ->toContain('Emirates Association of Accountants and Auditors')
        ->toContain('AED 100,000 to AED 2,000,000')
        ->toContain('Arabic and English');

    // The dated tax obligations behind the demand.
    expect($content)->toContain('31 December 2029')
        ->toContain('AED 50 million')
        ->toContain('30 October 2026')
        ->toContain('1 January 2027')
        ->toContain('1 July 2027')
        ->toContain('15 per cent');

    // Tax-free in the UAE is not tax-free at home, and DIFC is its own system.
    expect($content)->toContain('182 days')
        ->toContain('183 days')
        ->toContain('DEWS')
        ->toContain('AED 6,000');

    // The apply link goes to the UAE site rather than the American one.
    expect($content)->toContain('https://ae.indeed.com/q-accountant-jobs.html')
        ->not->toContain('www.indeed.com/jobs?q=accountant');

    expect(Job::where('position', 'like', 'Accountant%')->value('description'))
        ->toContain('directly or indirectly')
        ->toContain('licence');
});

it('corrects US police pay, who may apply and the firearms rule for visa holders', function () {
    $content = guideContent('police-officer-jobs-in-usa', Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class);

    // The measured medians sit well above the draft's bands.
    expect($content)->toContain('$76,210')
        ->toContain('$93,790')
        ->toContain('$47,510')
        ->toContain('$88,400')
        ->toContain('$119,500');

    // The top states by headcount are not the top states by pay.
    expect($content)->toContain('$79,200')
        ->toContain('$76,160')
        ->toContain('$75,320');

    // Citizenship differs sharply by agency, and federal officers must be citizens.
    expect($content)->toContain('federal officers must be US citizens')
        ->toContain('legally authorized to work in the United States')
        ->toContain('1 January 2026')
        ->toContain('1 January 2024');

    // A nonimmigrant visa holder cannot lawfully carry the academy's firearm.
    expect($content)->toContain('18 U.S.C. 922(g)(5)')
        ->toContain('nonimmigrant visa')
        ->toContain('26 September 2025')
        ->toContain('18 U.S.C. 922(g)(9)');

    // Age, federal training and certificates that do not travel.
    expect($content)->toContain('60 semester hours')
        ->toContain('20 years and 6 months')
        ->toContain('no longer includes sit-ups')
        ->toContain('160-hour');

    expect(Job::where('position', 'like', 'Police Officer%')->value('description'))
        ->toContain('US citizen')
        ->toContain('firearm');
});

it('corrects Australian retail sales pay, commission-only rules and real estate registration', function () {
    $content = guideContent('sales-jobs-in-australia', Database\Seeders\SalesJobsAustraliaBlogSeeder::class);

    // Every research placeholder has been replaced with a sourced figure.
    expect($content)->not->toContain('@@');

    // The $50,000 retail floor is below both the minimum wage and the award.
    expect($content)->toContain('$52,254.80')
        ->toContain('$1,056.80')
        ->toContain('$27.81')
        ->toContain('$54,953.60')
        ->toContain('$4,953.60 short')
        ->toContain('4.75 per cent')
        ->toContain('$34.76');

    // Commission-only pay is narrow, and each award treats it differently.
    expect($content)->toContain('no commission-only provision')
        ->toContain('$1,122.80')
        ->toContain('125 per cent')
        ->toContain('$72,741.50')
        ->toContain('31.5 per cent')
        ->toContain('at least 21')
        ->toContain('section 324');

    // The entry-level registration has a different name in each state and does not always cross borders.
    expect($content)->toContain('Assistant Agent certificate of registration')
        ->toContain("agent's representative")
        ->toContain('real estate salesperson registration')
        ->toContain('sales representative registration')
        ->toContain('registered sales representative')
        ->toContain('Queensland is not part of the scheme');

    // Sponsorship splits by occupation, and working holiday makers face a six-month limit.
    expect($content)->toContain('225412')
        ->toContain('225213')
        ->toContain('612115')
        ->toContain('621111')
        ->toContain('six months with one employer');

    // Official measured pay rather than job board ranges.
    expect($content)->toContain('August 2025')
        ->toContain('$631');

    expect(Job::where('position', 'like', 'Sales Consultant%')->value('description'))
        ->not->toContain('@@')
        ->toContain('$1,056.80')
        ->toContain('not by JobGader');
});

it('corrects UK healthcare assistant pay, the care salary floor, the checks and the HCA visa rule', function () {
    $content = guideContent('healthcare-assistant-jobs-in-uk', Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class);

    // The 2026/27 Agenda for Change rates replace the draft's older bands.
    expect($content)->toContain('3.3% pay award')
        ->toContain('&pound;25,272')
        ->toContain('&pound;25,760')
        ->toContain('&pound;27,476')
        ->toContain('41%')
        ->toContain('83%');

    // A GBP 21,000 full-time care salary is below the National Living Wage.
    expect($content)->toContain('&pound;24,784.50')
        ->toContain('&pound;3,784.50 more than &pound;21,000')
        ->toContain('&pound;21,157.50');

    // Only Band 3 and above in code 6131 can be sponsored; care workers closed.
    expect($content)->toContain('6131')
        ->toContain('Band 1 and Band 2 jobs cannot be sponsored')
        ->toContain('&pound;25,000 a year')
        ->toContain('22 July 2028')
        ->toContain('/blog/caregiver-jobs-in-uk-with-visa-sponsorship')
        ->toContain('does not repeat it');

    // Scotland and Northern Ireland do not use the DBS, and the Care Certificate grew.
    expect($content)->toContain('Protecting Vulnerable Groups (PVG) scheme')
        ->toContain('AccessNI')
        ->toContain('16 standards');

    expect(Job::where('position', 'like', 'Healthcare Assistant%')->value('description'))
        ->toContain('&pound;25,272')
        ->toContain('not by JobGader');
});

it('corrects US registered nurse pay, the states, the multistate license and the shortage', function () {
    $content = guideContent('registered-nurse-jobs-in-usa', Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class);

    // BLS May 2025 pay sits well above the draft's bands.
    expect($content)->toContain('$97,550')
        ->toContain('$101,420')
        ->toContain('$68,940');

    // Top states by headcount are not the top states by pay.
    expect($content)->toContain('Florida employs more than New York')
        ->toContain('$150,280');

    // The compact needs residence in a member state, and the biggest markets are outside it.
    expect($content)->toContain('43 jurisdictions')
        ->toContain('You must live in a compact state')
        ->toContain('California, New York, Illinois');

    // The exam fee, the shortage projection and the tax home rule.
    expect($content)->toContain('1 February 2027')
        ->toContain('$350')
        ->toContain('Pakistan')
        ->toContain('8% shortage in 2028')
        ->toContain('3% by 2038')
        ->toContain('tax home');

    // The visa route stays on the older guide.
    expect($content)->toContain('/blog/nurse-jobs-in-the-us')
        ->toContain('does not repeat it');

    expect(Job::where('position', 'like', 'Registered Nurse — Hospitals, Outpatient%')->value('description'))
        ->toContain('$97,550')
        ->toContain('not by JobGader');
});

it('corrects US help desk pay, the states, the outlook and the A+ exams', function () {
    $content = guideContent('help-desk-technician-jobs-in-usa', Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class);

    // BLS May 2025 pay: the draft's Tier 1 floor is below the bottom tenth.
    expect($content)->toContain('$61,860')
        ->toContain('$29.74')
        ->toContain('$40,980')
        ->toContain('$76,220');

    // Virginia is not a top five state, and DC pays the most.
    expect($content)->toContain('Virginia is 12th')
        ->toContain('Pennsylvania')
        ->toContain('$85,690');

    // The BLS projects a decline, not high demand.
    expect($content)->toContain('3% from 2025 to 2035')
        ->toContain('such as chatbots')
        ->toContain('48,700 openings a year');

    // A diploma qualifies with certifications, and A+ moved to V15.
    expect($content)->toContain('some college courses')
        ->toContain('a high school diploma plus relevant IT certifications')
        ->toContain('220-1201 (Core 1)')
        ->toContain('25 March 2025');

    expect(Job::where('position', 'like', 'Help Desk Technician%')->value('description'))
        ->toContain('$61,860')
        ->toContain('not by JobGader');
});

it('corrects UK housekeeper pay, the live-in offset, the checks and the visa codes', function () {
    $content = guideContent('housekeeper-jobs-in-uk', Database\Seeders\HousekeeperJobsUkBlogSeeder::class);

    // Three of the draft's four salary floors are below the National Living Wage.
    expect($content)->toContain('&pound;24,784.50')
        ->toContain('&pound;5,784.50 short')
        ->toContain('&pound;4,784.50 short')
        ->toContain('&pound;26,436.80')
        ->toContain('&pound;25,335');

    // Only accommodation counts towards the minimum wage, and only up to the offset.
    expect($content)->toContain('&pound;11.10 a day')
        ->toContain('&pound;77.70 a week')
        ->toContain('Meals do not count at all')
        ->toContain('&pound;20,744.10');

    // Enhanced checks are not the rule, and a household cannot request one.
    expect($content)->toContain('enhanced check without the barred lists')
        ->toContain('gets a <strong>standard check</strong>')
        ->toContain('<strong>cannot request a DBS check</strong>')
        ->toContain('Protecting Vulnerable Groups (PVG) scheme');

    // Housekeepers, cleaners and supervisors are all in Table 6.
    expect($content)->toContain('6231 Housekeepers and related occupations')
        ->toContain('9223 Cleaners and domestics')
        ->toContain('6240 Cleaning and housekeeping managers and supervisors')
        ->toContain('Overseas Domestic Worker visa')
        ->toContain('cannot be extended')
        ->not->toContain('9233');

    // NHS banding, the tips law and day-one sick pay.
    expect($content)->toContain('Band 1 closed to new starters in England on 1 December 2018')
        ->toContain('&pound;25,272')
        ->toContain('1 October 2024')
        ->toContain('6 April 2026');

    expect(Job::where('position', 'like', 'Housekeeper%')->value('description'))
        ->toContain('&pound;24,784.50')
        ->toContain('not by JobGader');
});

it('corrects US teacher pay, the hiring states, the outlook, the exams and the visa routes', function () {
    $content = guideContent('teacher-jobs-in-usa', Database\Seeders\TeacherJobsUsaBlogSeeder::class);

    // BLS May 2025 medians and the NEA average sit above the draft's experienced band.
    expect($content)->toContain('$63,970')
        ->toContain('$72,040')
        ->toContain('$74,495')
        ->toContain('$48,112');

    // Illinois, not Arizona, is the fifth largest employer; Washington pays near the top.
    expect($content)->toContain('Illinois:</strong> 61,520')
        ->toContain('Arizona ranks 23rd')
        ->toContain('41st highest mean wage')
        ->toContain('Washington ($96,589)');

    // No projected growth, and the shortages are subject-specific.
    expect($content)->toContain('little or no change')
        ->toContain('99,400 openings a year')
        ->toContain('Special education:</strong> reported by 40 states');

    // Praxis is not universal, and counselors need a master's degree.
    expect($content)->toContain('CBEST and CSET')
        ->toContain('TExES')
        ->toContain('FTCE')
        ->toContain('NYSTCE')
        ->toContain('<strong>master\'s degree</strong> as the typical entry requirement for school counselors');

    // The J-1 Teacher program and the H-1B lottery and payment.
    expect($content)->toContain('two years (24 months)')
        ->toContain('<strong>three years</strong>')
        ->toContain('weights the lottery by wage level')
        ->toContain('$100,000 payment')
        ->toContain('24 July 2026');

    expect(Job::where('position', 'like', 'Teacher — Elementary%')->value('description'))
        ->toContain('$63,970')
        ->toContain('not by JobGader');
});

it('corrects US administrative assistant pay, the outlook and the best-paying states', function () {
    $content = guideContent('administrative-assistant-jobs-in-usa', Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder::class);

    // Executive assistants and office managers earn more than the draft's bands.
    expect($content)->toContain('$76,590')
        ->toContain('$50,560')
        ->toContain('$69,500')
        ->toContain('$114,130')
        ->toContain('$47,540');

    // The BLS projects a decline, not growth, except for medical secretaries.
    expect($content)->toContain('<strong>2% decline</strong>')
        ->toContain('<strong>down 6%</strong>')
        ->toContain('<strong>down 5%</strong>')
        ->toContain('<strong>up 5%</strong>')
        ->toContain('artificial intelligence systems and digital tools');

    // The top hiring states are right; the best-paying are not the same.
    expect($content)->toContain('District of Columbia ($61,270)')
        ->toContain('Texas 35th at $45,850');

    // Certification details and the contractor warning.
    expect($content)->toContain('$375 for members and $575 for non-members')
        ->toContain('independent contracts');

    expect(Job::where('position', 'like', 'Administrative Assistant%')->value('description'))
        ->toContain('$76,590')
        ->toContain('not by JobGader');
});

it('corrects German DevOps pay, the Blue Card threshold, the city ranking and the shortage', function () {
    $content = guideContent('devops-engineer-jobs-in-germany', Database\Seeders\DevOpsEngineerJobsGermanyBlogSeeder::class);

    // Official Entgeltatlas figures replace the draft's unsourced bands.
    expect($content)->toContain('KldB 43323')
        ->toContain('&euro;5,864 a month')
        ->toContain('&euro;70,368 a year')
        ->toContain('&euro;3,977');

    // The junior floor misses the 2026 Blue Card minimum for IT specialists.
    expect($content)->toContain('&euro;45,934.20')
        ->toContain('&euro;50,700')
        ->toContain('&euro;934.20 below');

    // Stuttgart, not Munich, tops the cities; NRW employs the most.
    expect($content)->toContain('Stuttgart:</strong> &euro;6,394')
        ->toContain('Frankfurt am Main:</strong> &euro;5,897')
        ->toContain('North Rhine-Westphalia');

    // The official shortage analysis no longer lists software development.
    expect($content)->toContain('no longer any shortage in software development')
        ->toContain('109,000 unfilled IT jobs');

    // Blue Card rules, the Opportunity Card and net pay.
    expect($content)->toContain('<strong>27 months</strong>')
        ->toContain('<strong>21 months</strong>')
        ->toContain('&euro;208')
        ->toContain('&euro;1,091 a month')
        ->toContain('&euro;1,230 a month');

    expect(Job::where('position', 'like', 'DevOps Engineer — Cloud, CI/CD%')->value('description'))
        ->toContain('&euro;45,934.20')
        ->toContain('not by JobGader');
});

it('corrects federal police pay, the 2026 raise, the age limits and the agency details', function () {
    $content = guideContent('federal-police-jobs-in-usa', Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class);

    // The lowest GS-5 law enforcement pay anywhere is above the draft's $45,000 floor.
    expect($content)->toContain('$42,919')
        ->toContain('17.06%')
        ->toContain('$50,241')
        ->toContain('$51,632');

    // Most federal officers got 3.8% in 2026, not the 1% GS raise.
    expect($content)->toContain('3.8%')
        ->toContain('Executive Order 14368');

    // Special agents earn availability pay, and the FBI range starts above $100,000.
    expect($content)->toContain('25%')
        ->toContain('$103,236')
        ->toContain('$197,200')
        ->toContain('$45,000');

    // Age limits differ by agency, and ICE removed its cap.
    expect($content)->toContain('before their 38th birthday')
        ->toContain('before their 40th birthday')
        ->toContain('no maximum age');

    // Training, the Park Police and the hiring surge.
    expect($content)->toContain('Quantico')
        ->toContain('59 training days')
        ->toContain('San Francisco')
        ->toContain('12,000');

    expect(Job::where('position', 'like', 'Federal Police Officer and Special Agent%')->value('description'))
        ->toContain('$50,241')
        ->toContain('not by JobGader');
});

it('corrects Canadian welder certification, pay by province, the outlook and the LMIA rules', function () {
    $content = guideContent('welder-jobs-in-canada', Database\Seeders\WelderJobsCanadaBlogSeeder::class);

    // Red Seal is compulsory only in Alberta and Quebec among the main provinces.
    expect($content)->toContain('<strong>Compulsory</strong> in <strong>Alberta</strong> and <strong>Quebec</strong>')
        ->toContain('<strong>Voluntary</strong> in <strong>Ontario</strong>')
        ->toContain('pass mark of 70%');

    // Job Bank medians by province replace the draft's bands.
    expect($content)->toContain('median <strong>$38.00</strong>')
        ->toContain('median <strong>$28.00</strong>')
        ->toContain('$52.18');

    // The official outlook contradicts the draft's demand claims.
    expect($content)->toContain('Nova Scotia:</strong> <strong>Good</strong>')
        ->toContain('Alberta:</strong> <strong>Limited</strong>')
        ->toContain('Ontario:</strong> <strong>Very limited</strong>');

    // Tickets, the LMIA streams and Express Entry.
    expect($content)->toContain('CSA W47.1')
        ->toContain('Grade B')
        ->toContain('low-wage stream')
        ->toContain('Toronto (7.3%)')
        ->toContain('$1,000 LMIA fee')
        ->toContain('25 March 2025')
        ->toContain('477');

    expect(Job::where('position', 'like', 'Welder — Structural%')->value('description'))
        ->toContain('$38 an hour')
        ->toContain('not by JobGader');
});

it('corrects bus driver licence classes, pay by province and city, the outlook and hiring from abroad', function () {
    $content = guideContent('bus-driver-jobs-in-canada', Database\Seeders\BusDriverJobsCanadaBlogSeeder::class);

    // Ontario licenses bus drivers under letter classes, not Class 2 or 4.
    expect($content)->toContain('Ontario: Classes B, C, E and F')
        ->toContain('<strong>at least 21</strong>')
        ->toContain('6.25 hours')
        ->toContain('Class 4B')
        ->toContain('11 May 2026');

    // Job Bank medians by province and city, and the agencies' top rates.
    expect($content)->toContain('median <strong>$31.11</strong>')
        ->toContain('median <strong>$22.43</strong>')
        ->toContain('<strong>$30.06</strong>')
        ->toContain('<strong>$41.72</strong>')
        ->toContain('<strong>$37.91</strong>');

    // The official outlook, the part-time share and Greyhound's exit.
    expect($content)->toContain('Ontario:</strong> <strong>Good</strong>')
        ->toContain('Quebec:</strong> <strong>Moderate</strong>')
        ->toContain('<strong>37%</strong>')
        ->toContain('13 May 2021');

    // Hiring from abroad.
    expect($content)->toContain('<strong>Class G</strong>')
        ->toContain('low-wage stream')
        ->toContain('Toronto (7.3%)')
        ->toContain('Workforce Priority')
        ->toContain('not drivers');

    expect(Job::where('position', 'like', 'Bus Driver — Transit%')->value('description'))
        ->toContain('$22.43')
        ->toContain('not by JobGader');
});

it('corrects remote job pay, the telework trend, who can be hired and the scam data', function () {
    $content = guideContent('remote-jobs-in-usa', Database\Seeders\RemoteJobsUsaBlogSeeder::class);

    // BLS medians sit above the draft's bands.
    expect($content)->toContain('<strong>$135,980</strong>')
        ->toContain('<strong>$82,460</strong>')
        ->toContain('<strong>$44,770</strong>')
        ->toContain('$78,760');

    // Telework is large but flat, not rapidly growing.
    expect($content)->toContain('<strong>21.6%</strong>')
        ->toContain('22.1%')
        ->toContain('17.9%')
        ->toContain('<strong>60.8%</strong>')
        ->toContain('<strong>20 January 2025</strong>');

    // Work authorization and the tax form for workers abroad.
    expect($content)->toContain('<strong>Form I-9</strong>')
        ->toContain('<strong>1 August 2023</strong>')
        ->toContain('<strong>W-8BEN</strong>');

    // Employment rules and scams.
    expect($content)->toContain('<strong>15.3%</strong>')
        ->toContain('<strong>$2,000</strong>')
        ->toContain('<strong>20 minutes or less</strong>')
        ->toContain('$501 million in 2024');

    expect(Job::where('position', 'like', 'Remote Jobs — Customer Service%')->value('description'))
        ->toContain('$135,980')
        ->toContain('not by JobGader');
});

it('corrects the sponsorship targets, the Global Talent Stream, where LMIAs go and the language scores', function () {
    $content = guideContent('visa-sponsorship-jobs-in-canada', Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class);

    // The 2026 target, and the stream the draft called LMIA-exempt.
    expect($content)->toContain('<strong>380,000</strong>')
        ->toContain('<strong>still needs an LMIA</strong>')
        ->toContain('<strong>10 business days</strong>');

    // Where positive LMIAs actually went in 2025.
    expect($content)->toContain('<strong>54,817</strong>')
        ->toContain('<strong>61,584</strong>')
        ->toContain('Mexico and 11 Caribbean countries');

    // The low-wage limits and Job Bank pay.
    expect($content)->toContain('Toronto (7.3%)')
        ->toContain('<strong>A 10% cap</strong>')
        ->toContain('<strong>$43.27</strong>');

    // Language, the outlook, spouses and fraud.
    expect($content)->toContain('<strong>4.0</strong> in reading')
        ->toContain('<strong>Very good</strong>')
        ->toContain('<strong>21 January 2025</strong>')
        ->toContain('No one can guarantee you a job or a visa to Canada.');

    expect(Job::where('position', 'like', 'Visa Sponsorship Jobs — LMIA%')->value('description'))
        ->toContain('$43.27')
        ->toContain('not by JobGader');
});

it('corrects Australian entry-level pay, the super rate, junior rates and the mining claims', function () {
    $content = guideContent('no-experience-jobs-in-australia', Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class);

    // Award rates from 1 July 2026 replace the draft's salary table.
    expect($content)->toContain('<strong>4.75%</strong>')
        ->toContain('$26.44 an hour')
        ->toContain('<strong>$1,056.80</strong>')
        ->toContain('<strong>$1,307.80</strong>')
        ->toContain('<strong>$33.05 an hour</strong>');

    // Super and junior rates.
    expect($content)->toContain('12% on 1 July 2025')
        ->toContain('<strong>60%</strong>');

    // Aged care checks, the labour market and the mining tickets.
    expect($content)->toContain('NDIS Worker Screening Check')
        ->toContain('<strong>4.5%</strong>')
        ->toContain('<strong>10.4%</strong>')
        ->toContain('<strong>Standard 11</strong>')
        ->toContain('<strong>White Card</strong>')
        ->toContain('<strong>48 hours a fortnight</strong>');

    expect(Job::where('position', 'like', 'Entry-Level Jobs — Retail%')->value('description'))
        ->toContain('$27.81')
        ->toContain('not by JobGader');
});

it('corrects Saudi kitchen helper pay, who pays the visa, the health certificate and the age claim', function () {
    $content = guideContent('kitchen-helper-jobs-in-saudi-arabia', Database\Seeders\KitchenHelperJobsSaudiBlogSeeder::class);

    // The referral wage floor replaces the draft's band.
    expect($content)->toContain('SAR 1,600')
        ->toContain('SAR 2,000')
        ->toContain('no minimum wage for expatriate workers');

    // Article 40, the certificate and the hours.
    expect($content)->toContain('<strong>Article 40</strong>')
        ->toContain('Balady')
        ->toContain('<strong>8 hours a day or 48 a week</strong>')
        ->toContain('50% of the basic wage')
        ->toContain('<strong>21 days</strong>')
        ->toContain('<strong>180 days</strong>');

    // Saudization, the sector data and the age claim.
    expect($content)->toContain('<strong>22 April 2026</strong>')
        ->toContain('<strong>983,253 people working in tourism activities</strong>')
        ->toContain('<strong>no one under 15</strong>')
        ->toContain('Rs 30,000');

    expect(Job::where('position', 'like', 'Kitchen Helper — Hotels%')->value('description'))
        ->toContain('SAR 1,600')
        ->toContain('not by JobGader');
});

it('keeps the data entry cluster from restating the guide it hangs off', function () {
    // The remote guide owns the scam mechanics and the worldwide geography.
    // If the two US and Pakistan pages repeat them, three pages compete for
    // one query instead of covering three different intents.
    $this->seed(Database\Seeders\DataEntryJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\DataEntryJobsPakistanBlogSeeder::class);

    $usa = Blog::where('slug', 'data-entry-jobs-in-usa')->value('content');
    $pakistan = Blog::where('slug', 'data-entry-jobs-in-pakistan')->value('content');

    expect($usa)->toContain('/blog/remote-data-entry-jobs')
        ->toContain('does not repeat it');

    expect($pakistan)->toContain('/blog/remote-data-entry-jobs')
        ->toContain('does not repeat it');

    // And the Pakistan page uses the US projection as contrast, not as its
    // own subject, so the two do not compete on the same argument.
    expect($pakistan)->toContain('/blog/data-entry-jobs-in-usa');
    expect($usa)->toContain('/blog/data-entry-jobs-in-pakistan');
});

it('wires every new guide into the existing cluster in both directions', function () {
    foreach ([
        Database\Seeders\DeliveryDriverJobsUkBlogSeeder::class,
        Database\Seeders\CustomerServiceJobsCanadaBlogSeeder::class,
        Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class,
        Database\Seeders\DataEntryJobsUsaBlogSeeder::class,
        Database\Seeders\DataEntryJobsPakistanBlogSeeder::class,
        Database\Seeders\SecurityGuardJobsUaeBlogSeeder::class,
        Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
        Database\Seeders\RetailJobsUsaBlogSeeder::class,
        Database\Seeders\FactoryWorkerJobsGermanyBlogSeeder::class,
        Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class,
        Database\Seeders\DataScientistJobsUsaBlogSeeder::class,
        Database\Seeders\ElectricianJobsUkBlogSeeder::class,
        Database\Seeders\PlumberJobsAustraliaBlogSeeder::class,
        Database\Seeders\ReceptionistJobsUaeBlogSeeder::class,
        Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class,
        Database\Seeders\TeacherJobsPakistanBlogSeeder::class,
        Database\Seeders\NurseJobsSaudiBlogSeeder::class,
        Database\Seeders\ItSupportJobsUkBlogSeeder::class,
        Database\Seeders\CashierJobsUsaBlogSeeder::class,
        Database\Seeders\CookJobsUkBlogSeeder::class,
        Database\Seeders\AccountantJobsUaeBlogSeeder::class,
        Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        Database\Seeders\SalesJobsAustraliaBlogSeeder::class,
        Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
        Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class,
        Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class,
        Database\Seeders\HousekeeperJobsUkBlogSeeder::class,
        Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder::class,
        Database\Seeders\DevOpsEngineerJobsGermanyBlogSeeder::class,
        Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        Database\Seeders\WelderJobsCanadaBlogSeeder::class,
        Database\Seeders\BusDriverJobsCanadaBlogSeeder::class,
        Database\Seeders\RemoteJobsUsaBlogSeeder::class,
        Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
        Database\Seeders\KitchenHelperJobsSaudiBlogSeeder::class,
        // The established posts that should now point back at them.
        Database\Seeders\NurseJobsUsBlogSeeder::class,
        Database\Seeders\HealthcareJobsUkBlogSeeder::class,
        Database\Seeders\NetworkEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\RetailJobsUsaBlogSeeder::class,
        Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class,
        Database\Seeders\CleanerLondonBlogSeeder::class,
        Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder::class,
        Database\Seeders\RemoteDataEntryJobsBlogSeeder::class,
        Database\Seeders\RemoteCustomerServiceJobsBlogSeeder::class,
        Database\Seeders\WarehouseUkBlogSeeder::class,
        Database\Seeders\SecurityGuardJobsSaudiBlogSeeder::class,
        Database\Seeders\DriverJobsSaudiBlogSeeder::class,
        Database\Seeders\UnskilledJobsUsaBlogSeeder::class,
        Database\Seeders\GovernmentJobsPakistanBlogSeeder::class,
        Database\Seeders\AtsResumeWriterCanadaBlogSeeder::class,
        Database\Seeders\PythonDeveloperJobsUsaBlogSeeder::class,
        Database\Seeders\CaregiverUkBlogSeeder::class,
        Database\Seeders\TeacherJobsPakistanBlogSeeder::class,
        Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
        Database\Seeders\VirtualAssistantJobsPakistanBlogSeeder::class,
        Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class,
        Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class,
        Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class,
        Database\Seeders\ElectricianJobsUkBlogSeeder::class,
        Database\Seeders\RemoteJobsNoExperienceBlogSeeder::class,
    ] as $seeder) {
        $this->seed($seeder);
    }

    $inbound = [
        'delivery-driver-jobs-in-uk' => ['warehouse-jobs-uk-visa-sponsorship', 'driver-jobs-in-saudi-arabia-for-foreigners'],
        'customer-service-jobs-in-canada' => ['remote-customer-service-jobs'],
        'data-entry-jobs-in-usa' => ['remote-data-entry-jobs', 'unskilled-jobs-in-usa-for-foreigners'],
        'data-entry-jobs-in-pakistan' => ['remote-data-entry-jobs', 'government-jobs-in-pakistan'],
        'security-guard-jobs-in-uae' => ['security-guard-jobs-in-saudi-arabia', 'driver-jobs-in-saudi-arabia-for-foreigners'],
        'retail-jobs-in-usa' => ['remote-customer-service-jobs', 'unskilled-jobs-in-usa-for-foreigners'],
        'factory-worker-jobs-in-germany' => ['warehouse-jobs-uk-visa-sponsorship'],
        'farm-worker-jobs-in-canada' => ['customer-service-jobs-in-canada', 'ats-resume-writer-jobs-in-canada'],
        'data-scientist-jobs-in-usa' => ['data-entry-jobs-in-usa', 'python-developer-jobs-in-usa'],
        'electrician-jobs-in-uk' => ['delivery-driver-jobs-in-uk', 'warehouse-jobs-uk-visa-sponsorship'],
        'plumber-jobs-in-australia' => ['construction-worker-jobs-in-australia', 'office-assistant-jobs-in-australia', 'electrician-jobs-in-uk'],
        'receptionist-jobs-in-uae' => ['security-guard-jobs-in-uae', 'office-assistant-jobs-in-australia'],
        'delivery-driver-jobs-in-usa' => ['delivery-driver-jobs-in-uk', 'retail-jobs-in-usa'],
        'teacher-jobs-in-pakistan' => ['government-jobs-in-pakistan', 'data-entry-jobs-in-pakistan'],
        'nurse-jobs-in-saudi-arabia' => ['nurse-jobs-in-the-us', 'healthcare-jobs-in-the-uk', 'security-guard-jobs-in-saudi-arabia'],
        'it-support-jobs-in-uk' => ['electrician-jobs-in-uk', 'remote-customer-service-jobs', 'network-engineer-jobs-in-usa'],
        'cashier-jobs-in-usa' => ['retail-jobs-in-usa', 'unskilled-jobs-in-usa-for-foreigners', 'delivery-driver-jobs-in-usa'],
        'cook-jobs-in-uk' => ['cleaner-jobs-in-london-no-experience-needed', 'warehouse-jobs-uk-visa-sponsorship', 'delivery-driver-jobs-in-uk'],
        'accountant-jobs-in-uae' => ['receptionist-jobs-in-uae', 'security-guard-jobs-in-uae', 'data-entry-jobs-in-pakistan'],
        'police-officer-jobs-in-usa' => ['security-guard-jobs-in-saudi-arabia', 'cybersecurity-analyst-jobs-in-usa', 'unskilled-jobs-in-usa-for-foreigners'],
        'sales-jobs-in-australia' => ['office-assistant-jobs-in-australia', 'construction-worker-jobs-in-australia', 'plumber-jobs-in-australia', 'retail-jobs-in-usa', 'customer-service-jobs-in-canada'],
        'healthcare-assistant-jobs-in-uk' => ['healthcare-jobs-in-the-uk', 'caregiver-jobs-in-uk-with-visa-sponsorship', 'cook-jobs-in-uk'],
        'registered-nurse-jobs-in-usa' => ['nurse-jobs-in-the-us', 'nurse-jobs-in-saudi-arabia', 'healthcare-jobs-in-the-uk'],
        'help-desk-technician-jobs-in-usa' => ['it-support-jobs-in-uk', 'network-engineer-jobs-in-usa', 'cybersecurity-analyst-jobs-in-usa'],
        'housekeeper-jobs-in-uk' => ['cleaner-jobs-in-london-no-experience-needed', 'cook-jobs-in-uk', 'healthcare-assistant-jobs-in-uk'],
        'teacher-jobs-in-usa' => ['teacher-jobs-in-pakistan', 'nurse-jobs-in-the-us', 'unskilled-jobs-in-usa-for-foreigners'],
        'administrative-assistant-jobs-in-usa' => ['data-entry-jobs-in-usa', 'office-assistant-jobs-in-australia', 'virtual-assistant-jobs-in-pakistan'],
        'devops-engineer-jobs-in-germany' => ['devops-engineer-jobs-in-usa', 'factory-worker-jobs-in-germany', 'cloud-engineer-jobs-in-usa'],
        'federal-police-jobs-in-usa' => ['police-officer-jobs-in-usa', 'cybersecurity-analyst-jobs-in-usa', 'teacher-jobs-in-usa'],
        'welder-jobs-in-canada' => ['farm-worker-jobs-in-canada', 'construction-worker-jobs-in-australia', 'electrician-jobs-in-uk'],
        'bus-driver-jobs-in-canada' => ['welder-jobs-in-canada', 'driver-jobs-in-saudi-arabia-for-foreigners', 'delivery-driver-jobs-in-usa'],
        'remote-jobs-in-usa' => ['remote-customer-service-jobs', 'remote-data-entry-jobs', 'remote-jobs-in-pakistan-with-no-experience'],
        'visa-sponsorship-jobs-in-canada' => ['farm-worker-jobs-in-canada', 'customer-service-jobs-in-canada', 'bus-driver-jobs-in-canada'],
        'no-experience-jobs-in-australia' => ['construction-worker-jobs-in-australia', 'office-assistant-jobs-in-australia', 'sales-jobs-in-australia'],
        'kitchen-helper-jobs-in-saudi-arabia' => ['driver-jobs-in-saudi-arabia-for-foreigners', 'security-guard-jobs-in-saudi-arabia', 'nurse-jobs-in-saudi-arabia'],
    ];

    foreach ($inbound as $target => $sources) {
        foreach ($sources as $source) {
            expect(Blog::where('slug', $source)->value('content'))->toContain('/blog/'.$target);
        }
    }
});

it('answers the shortage claim and prices the CDL guide off the federal wage data', function () {
    // The draft is built on an "ongoing driver shortage" and quotes $50,000
    // to $75,000, with $80,000 to $100,000+ for specialised work. The BLS
    // median is $58,640 and the top decile starts at $79,380, and the Labor
    // Department's own journal disputes the shortage. The draft also treats
    // CDL school as a preference, omits the age rule that decides what work
    // an applicant can take, and gives no hours-of-service limit at all.
    $this->seed(Database\Seeders\CdlDriverJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'cdl-driver-jobs-in-usa')->value('content'))
        ->toContain('58,640')
        ->toContain('40,140')
        ->toContain('79,380')
        ->toContain('2,221,200')
        ->toContain('4 per cent from 2025 to 2035')
        ->toContain('does not find evidence of a secular shortage')
        ->toContain('7 February 2022')
        ->toContain('Training Provider Registry')
        ->toContain('Safe Driver Apprenticeship Pilot')
        ->toContain('11 hours, after 10 consecutive hours off duty')
        ->toContain('49 CFR Part 1572')
        ->toContain('Drug and Alcohol Clearinghouse');
});

it('replaces the online jobs draft claims with the index, fees, SBP rules, tax rates and wage floors on record', function () {
    // The draft calls Pakistan a top freelancing country, quotes PKR earnings
    // bands with no source, lists the platforms without their fees and says
    // nothing about PayPal, State Bank accounts or tax. What is on record is a
    // 2019 growth ranking, Fiverr's 20 per cent and Upwork's 0 to 15 per cent,
    // the SBP freelancer framework and its 2026 update, and the section 154A
    // rates that PSEB registration and the Active Taxpayers' List decide.
    $this->seed(Database\Seeders\OnlineJobsPakistanBlogSeeder::class);

    expect(Blog::where('slug', 'online-jobs-in-pakistan')->value('content'))
        ->toContain('24 August 2019')
        ->toContain('fourth fastest-growing freelance market')
        ->toContain('47 per cent growth in freelance earnings')
        ->toContain('PKR 43,000')
        ->toContain('PKR 45,000')
        ->toContain('Freelance income has no minimum at all')
        ->toContain("Fiverr's commission is 20 per cent of the order amount")
        ->toContain('0 per cent to 15 per cent per contract')
        ->toContain('does not include Pakistan')
        ->toContain('BPRD Circular No. 5 of 2023')
        ->toContain('50 per cent of export proceeds or USD 5,000 a month, whichever is higher')
        ->toContain('from 35 per cent to 50 per cent')
        ->toContain('one-time declaration')
        ->toContain('one working day')
        ->toContain('above US$25,000')
        ->toContain('0.25 per cent for persons registered with the Pakistan Software Export Board and 1 per cent otherwise')
        ->toContain('tax years 2024 to 2026')
        ->toContain('extends that end year to 2029')
        ->toContain("Active Taxpayers' List");
});

it('replaces the mechanic draft pay bands and benefit claims with the skills test and the Labour Law articles', function () {
    // The draft quotes unsourced SAR bands, calls the pre-visa step a mere
    // attestation, and lists housing, transport and tickets as standard. On
    // record: the ministry's Professional Examination names automotive
    // mechanics, Article 40 puts recruitment costs on the employer, Articles
    // 147 and 148 limit housing and transport duties to certain workplaces,
    // SAR 4,000 is a Nitaqat rule for Saudis, and BE&OE licensing protects
    // Pakistani applicants.
    $this->seed(Database\Seeders\MechanicJobsSaudiBlogSeeder::class);

    expect(Blog::where('slug', 'mechanic-jobs-in-saudi-arabia')->value('content'))
        ->toContain('automotive mechanics')
        ->toContain('first launched in Pakistan, India and Bangladesh')
        ->toContain('12 July 2023')
        ->toContain('five specialisations out of 23 targeted by the ministry')
        ->toContain('additional requirement for the worker during the recruitment process')
        ->toContain('Article 89 says only that the Council of Ministers may set a minimum wage')
        ->toContain('23 November 2020')
        ->toContain('Nitaqat Saudization calculation')
        ->toContain('the fees for a change of profession')
        ->toContain('not less than 21 days a year')
        ->toContain('remote locations, mines, quarries and oil exploration centres')
        ->toContain('Article 40 covers the ticket home at the end of the contract')
        ->toContain('one third after two to five years')
        ->toContain('valid OEP licence list')
        ->toContain('Rule 29(4) of the Emigration Rules, 1979');
});

it('replaces the janitor draft pay band and tips claim with Job Bank data, minimum wages and the LMIA limits', function () {
    // The draft quotes $18 to $33 an hour, calls openings plentiful in every
    // city it names, promises gig cleaners 100% of tips and says nothing about
    // coming from abroad. On record: Job Bank's NOC 65312 wages and prospects,
    // the 2026 minimum wages, the scope of Ontario's platform-worker law, and
    // the low-wage LMIA and TEER 5 limits.
    $this->seed(Database\Seeders\JanitorJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'janitor-jobs-in-canada')->value('content'))
        ->toContain('NOC 65312')
        ->toContain('$21.27')
        ->toContain('$24.65')
        ->toContain('from $16.00 to $28.72')
        ->toContain('Completion of secondary school may be required')
        ->toContain('not regulated in Canada')
        ->toContain('Nova Scotia and New Brunswick:')
        ->toContain('$17.95 on 1 October 2026')
        ->toContain('federal minimum of $18.15')
        ->toContain('not a legal right')
        ->toContain('ride share, delivery and courier work')
        ->toContain('10 July to 8 October 2026')
        ->toContain('A one-year maximum')
        ->toContain('TEER 5')
        ->toContain('21 January 2025');
});

it('replaces the system administrator draft product names and package claims with the lifecycle dates and the labour law', function () {
    // The draft lists Azure and Office 365 under their old names, promises
    // 24/7 on-call work without the overtime limits, and the poster offers an
    // air ticket and accommodation as standard. On record: the Entra ID
    // rename, the Windows Server 2016 end of support, the Article 17 and 19
    // hours and overtime rules, the repatriation duty in Article 13(12), the
    // Emiratisation targets and the Green and Golden visa salaries.
    $this->seed(Database\Seeders\SystemAdministratorJobsUaeBlogSeeder::class);

    expect(Blog::where('slug', 'system-administrator-jobs-in-uae')->value('content'))
        ->toContain('Microsoft Entra ID')
        ->toContain('11 July 2023')
        ->toContain('12 January 2027')
        ->toContain('expire one year after')
        ->toContain('Federal Decree-Law No. 33 of 2021')
        ->toContain('8 hours a day or 48 hours a week')
        ->toContain('two hours a day')
        ->toContain('144 hours in any three weeks')
        ->toContain('at least 25 per cent')
        ->toContain('between 10 pm and 4 am')
        ->toContain('not a statutory entitlement')
        ->toContain('Article 13(12)')
        ->toContain('10 per cent by the end of 2026')
        ->toContain('information and communications')
        ->toContain('AED 30,000')
        ->toContain('AED 15,000');
});

it('replaces the medical receptionist draft demand and sponsorship claims with the award, occupation data and visa lists', function () {
    // The draft counts hundreds of new jobs a week, names no award, and says
    // listings mention visa sponsorship. On record: Jobs and Skills Australia's
    // occupation profile, the Health Services Award progression, the Privacy
    // Act's reach over every health service provider, the November 2025 bulk
    // billing changes, and an occupation missing from the Core Skills list.
    $this->seed(Database\Seeders\MedicalReceptionistJobsAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'medical-receptionist-jobs-in-australia')->value('content'))
        ->toContain('ANZSCO 542114')
        ->toContain('46,700')
        ->toContain('33 per cent work full-time')
        ->toContain('median age is 46')
        ->toContain('Health Professionals and Support Services Award 2020')
        ->toContain('Support services level 2 or level 3')
        ->toContain('$26.44 an hour')
        ->toContain('25 per cent casual loading')
        ->toContain('whether or not they are a small business')
        ->toContain('1 November 2025')
        ->toContain('12.5 per cent')
        ->toContain('not on the Core Skills Occupation List')
        ->toContain('$76,515');
});

it('replaces the receptionist draft salary bands, qualification and apply link with the awards and current codes', function () {
    // The draft starts full-time pay at AU$50,000, names no award, recommends a
    // superseded Certificate III in Business Administration and searches the
    // American Indeed site. On record: the Clerks Award rates from 1 July 2026,
    // the Hospitality Award front office grade, BSB30120, Jobs and Skills
    // Australia's occupation profile and the Core Skills Occupation List.
    $this->seed(Database\Seeders\ReceptionistJobsAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'receptionist-jobs-in-australia')->value('content'))
        ->toContain('Clerks &mdash; Private Sector Award 2020 (MA000002)')
        ->toContain('$1,024.70 a week, $26.97 an hour (Level 1 year 1)')
        ->toContain('$1,182.10')
        ->toContain('about $53,284 a year')
        ->toContain('$1,004.90 a week')
        ->toContain('front office grade 1')
        ->toContain('$1,029.10 a week')
        ->toContain('4.75 per cent')
        ->toContain('$33.71 an hour')
        ->toContain('superseded by the Certificate III in Business (BSB30120)')
        ->toContain('43 per cent work full-time')
        ->toContain('Receptionist occupations are not on the Core Skills Occupation List')
        ->toContain('https://au.indeed.com/q-receptionist-jobs.html')
        ->not->toContain('indeed.com/jobs?q=receptionist');

    $siblings = [
        'medical-receptionist-jobs-in-australia' => Database\Seeders\MedicalReceptionistJobsAustraliaBlogSeeder::class,
        'office-assistant-jobs-in-australia' => Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
        'receptionist-jobs-in-uae' => Database\Seeders\ReceptionistJobsUaeBlogSeeder::class,
        'no-experience-jobs-in-australia' => Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/receptionist-jobs-in-australia');
    }
});

it('replaces the dental assistant draft growth, regulation, exam and pay claims with the official record', function () {
    // The draft calls the job fastest-growing, lists Ontario among regulated
    // provinces, credits NDAEB with recognising programs, calls CPR universal
    // and understates pay. On record: the COPS projection, the RCDSO standard,
    // CDAC accreditation, the 2026 NDAEB exam order, Job Bank wages and IRCC's
    // health care category list.
    $this->seed(Database\Seeders\DentalAssistantJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'dental-assistant-jobs-in-canada')->value('content'))
        ->toContain('NOC 33100')
        ->toContain('18,300 job openings against 18,200 job seekers')
        ->toContain('dental assistants are not regulated')
        ->toContain('except Ontario and Quebec')
        ->toContain('The Commission on Dental Accreditation of Canada (CDAC) accredits dental assisting programs')
        ->toContain('New order from 1 January 2026.')
        ->toContain('do not need CPR to renew your practice permit')
        ->toContain('$27 ($52,650)')
        ->toContain('$32 ($62,400)')
        ->toContain('$17.60 an hour in Ontario')
        ->toContain("not on IRCC's list of occupations for the health care and social services category")
        ->toContain('ClearDent')
        ->not->toContain('Cleardent')
        ->not->toContain('indeed.com/jobs?q=dental');

    $siblings = [
        'medical-assistant-jobs-in-usa' => Database\Seeders\MedicalAssistantJobsUsaBlogSeeder::class,
        'janitor-jobs-in-canada' => Database\Seeders\JanitorJobsCanadaBlogSeeder::class,
        'customer-service-jobs-in-canada' => Database\Seeders\CustomerServiceJobsCanadaBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'healthcare-assistant-jobs-in-uk' => Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/dental-assistant-jobs-in-canada');
    }
});

it('replaces the school nurse draft pay bands, degree and certification claims with BLS school data and state rules', function () {
    // The draft quotes $45,000 to $65,000, says an ADN is sometimes enough and
    // treats NCSN as an entry credential. On record: BLS May 2025 wages for RNs
    // in elementary and secondary schools, the California preliminary
    // credential, NBCSN eligibility and fees, and the Workforce Study 2.0.
    $this->seed(Database\Seeders\SchoolNurseJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'school-nurse-jobs-in-usa')->value('content'))
        ->toContain('$69,340')
        ->toContain('$73,960')
        ->toContain('$48,010')
        ->toContain('$103,310')
        ->toContain('$97,550')
        ->toContain('School Nurse Services Credential')
        ->toContain('<strong>is not renewable</strong>')
        ->toContain('not an entry credential')
        ->toContain('1,000 hours of school nursing practice in the three years')
        ->toContain('$380 early bird')
        ->toContain('65.7 per cent of schools')
        ->toContain('18.1 per cent of schools')
        ->toContain('https://www.indeed.com/q-school-nurse-jobs.html')
        ->not->toContain('jobs?q=school+nurse');

    $siblings = [
        'registered-nurse-jobs-in-usa' => Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class,
        'nurse-jobs-in-the-us' => Database\Seeders\NurseJobsUsBlogSeeder::class,
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'medical-assistant-jobs-in-usa' => Database\Seeders\MedicalAssistantJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/school-nurse-jobs-in-usa');
    }
});

it('replaces the security specialist draft licensing, salary, benefit and certification claims with the regulators and the law', function () {
    // The draft leaves the emirates outside Dubai to "local authorities",
    // starts pay at AED 3,000, lists flights home as a benefit and treats
    // CISSP as an application extra. On record: SIRA and PSBD jurisdiction,
    // published guarding ranges, Federal Decree-Law No. 33 of 2021 and the
    // ISC2 experience rule.
    $this->seed(Database\Seeders\SecuritySpecialistJobsUaeBlogSeeder::class);

    expect(Blog::where('slug', 'security-specialist-jobs-in-uae')->value('content'))
        ->toContain('<strong>in the Emirate of Dubai</strong>')
        ->toContain('Abu Dhabi, Sharjah, Ajman, Umm Al Quwain, Ras Al Khaimah and Fujairah')
        ->toContain('There is no separate Sharjah security regulator.')
        ->toContain('A licence does not travel.')
        ->toContain('five years of cumulative paid work in two or more of its eight domains')
        ->toContain('Associate of ISC2')
        ->toContain('AED 1,800 to 3,000 a month')
        ->toContain('not a statutory entitlement')
        ->toContain('repatriation ticket')
        ->toContain('144 hours in any three weeks')
        ->toContain('calculated on <strong>basic salary alone</strong>')
        ->toContain('https://ae.indeed.com/q-security-specialist-jobs.html')
        ->not->toContain('indeed.com/jobs?q=security');

    $siblings = [
        'security-guard-jobs-in-uae' => Database\Seeders\SecurityGuardJobsUaeBlogSeeder::class,
        'system-administrator-jobs-in-uae' => Database\Seeders\SystemAdministratorJobsUaeBlogSeeder::class,
        'receptionist-jobs-in-uae' => Database\Seeders\ReceptionistJobsUaeBlogSeeder::class,
        'accountant-jobs-in-uae' => Database\Seeders\AccountantJobsUaeBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/security-specialist-jobs-in-uae');
    }
});

it('replaces the recruiter draft market, licensing, posting and designation claims with the official record', function () {
    // The draft calls the labour market tight, ignores recruiter licensing and
    // the 2026 posting rules, and treats CPHR as national. On record: the
    // August 2026 Labour Force Survey, Job Bank wages for NOC 12101, Ontario's
    // ESA licensing and posting rules, BC pay transparency and HRPA.
    $this->seed(Database\Seeders\RecruiterJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'recruiter-jobs-in-canada')->value('content'))
        ->toContain('<strong>unemployment rate of 6.4 per cent</strong>')
        ->toContain('<strong>down 42,000</strong>')
        ->toContain('NOC 12101, human resources and recruitment officers')
        ->toContain('$33.33 ($64,994)')
        ->toContain('Since <strong>1 July 2024</strong>')
        ->toContain('<strong>recruiter licence</strong>')
        ->toContain('In-house recruiters are exempt in Ontario.')
        ->toContain('<strong>$1,500</strong>')
        ->toContain('pay range no wider than $50,000 a year')
        ->toContain('Disclose the use of artificial intelligence')
        ->toContain('Not require Canadian experience')
        ->toContain('since 1 November 2023')
        ->toContain('CHRP, CHRL and CHRE')
        ->toContain('https://ca.indeed.com/q-recruiter-jobs.html')
        ->not->toContain('jobs?q=recruiter');

    $siblings = [
        'ats-resume-writer-jobs-in-canada' => Database\Seeders\AtsResumeWriterCanadaBlogSeeder::class,
        'customer-service-jobs-in-canada' => Database\Seeders\CustomerServiceJobsCanadaBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'qa-tester-jobs-in-canada' => Database\Seeders\QaTesterJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/recruiter-jobs-in-canada');
    }
});

it('replaces the heavy equipment operator draft demand, certification and crane claims with Job Bank, Red Seal and provincial rules', function () {
    // The draft promises steady demand coast to coast with premium Alberta and
    // BC work, leaves apprenticeships unnamed and gives two crane examples. On
    // record: Job Bank wages and outlooks for NOC 73400, the Red Seal operator
    // trades, compulsory crane certification, Ontario working at heights and
    // IRCC's trade occupations list.
    $this->seed(Database\Seeders\HeavyEquipmentOperatorJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'heavy-equipment-operator-jobs-in-canada')->value('content'))
        ->toContain('NOC 73400, heavy equipment operators')
        ->toContain('Alberta, British Columbia, Quebec, Manitoba, Northwest Territories')
        ->toContain('$32.50 ($67,600)')
        ->toContain('$36.00 ($74,880)')
        ->toContain('Heavy Equipment Operator (Tractor-Loader-Backhoe)')
        ->toContain('<strong>not designated in Alberta or British Columbia</strong>')
        ->toContain('compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Alberta and British Columbia')
        ->toContain('<strong>valid for three years</strong>')
        ->toContain('<strong>heavy-duty equipment mechanics (72401)</strong>')
        ->toContain('separate occupation (NOC 72500)')
        ->toContain('https://ca.indeed.com/q-heavy-equipment-operator-jobs.html')
        ->not->toContain('jobs?q=heavy+equipment');

    $siblings = [
        'welder-jobs-in-canada' => Database\Seeders\WelderJobsCanadaBlogSeeder::class,
        'bus-driver-jobs-in-canada' => Database\Seeders\BusDriverJobsCanadaBlogSeeder::class,
        'farm-worker-jobs-in-canada' => Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class,
        'construction-worker-jobs-in-australia' => Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/heavy-equipment-operator-jobs-in-canada');
    }
});

it('replaces the forklift operator draft pay, certification, demand and visa claims with BLS, OSHA and DOL rules', function () {
    // The draft quotes $32,000 to $55,000, names Texas as high-paying, treats
    // OSHA certification as a portable credential, ignores the age rule and
    // its banner promises sponsorship. On record: BLS May 2025 wages and
    // projections, 29 CFR 1910.178(l), Hazardous Occupations Order No. 7.
    $this->seed(Database\Seeders\ForkliftOperatorJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'forklift-operator-jobs-in-usa')->value('content'))
        ->toContain('$46,420')
        ->toContain('$36,840')
        ->toContain('$62,520')
        ->toContain('<strong>Texas median is $45,450</strong>')
        ->toContain('29 CFR 1910.178(l)')
        ->toContain('OSHA does not certify operators.')
        ->toContain('<strong>at least once every three years</strong>')
        ->toContain('Hazardous Occupations Order No. 7')
        ->toContain('<strong>under 18</strong>')
        ->toContain('grow about 1 per cent')
        ->toContain('EB-3 "other worker" green card')
        ->toContain('https://www.indeed.com/q-forklift-operator-jobs.html')
        ->not->toContain('jobs?q=forklift');

    $siblings = [
        'cdl-driver-jobs-in-usa' => Database\Seeders\CdlDriverJobsUsaBlogSeeder::class,
        'delivery-driver-jobs-in-usa' => Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class,
        'unskilled-jobs-in-usa-for-foreigners' => Database\Seeders\UnskilledJobsUsaBlogSeeder::class,
        'heavy-equipment-operator-jobs-in-canada' => Database\Seeders\HeavyEquipmentOperatorJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/forklift-operator-jobs-in-usa');
    }
});

it('replaces the work from home draft pay bands and missing tax, expense, break and scam rules with the official record', function () {
    // The draft's entry band sits below BLS medians, and it skips contractor
    // tax, who pays for the home office, paid breaks at home and task scams.
    // On record: BLS May 2025 medians, IRS 1099 thresholds and home office
    // rules, California Labor Code section 2802, FAB 2023-1 and FTC data.
    $this->seed(Database\Seeders\WorkFromHomeJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'work-from-home-jobs-in-usa')->value('content'))
        ->toContain('$44,770')
        ->toContain('$41,340')
        ->toContain('$135,980')
        ->toContain('<strong>15.3 per cent self-employment tax</strong>')
        ->toContain('<strong>$2,000 or more</strong>')
        ->toContain('<strong>$20,000 and 200 transactions</strong>')
        ->toContain('employees are not eligible for the home office deduction')
        ->toContain('California Labor Code section 2802')
        ->toContain('Field Assistance Bulletin 2023-1')
        ->toContain('Short breaks of 20 minutes or less are paid.')
        ->toContain('<strong>$90 million in 2020 to $501 million in 2024</strong>')
        ->toContain('Task scams.')
        ->toContain('/blog/remote-jobs-in-usa')
        ->toContain('https://www.indeed.com/q-work-from-home-jobs.html')
        ->not->toContain('jobs?q=work+from+home');

    $siblings = [
        'remote-jobs-in-usa' => Database\Seeders\RemoteJobsUsaBlogSeeder::class,
        'work-from-home-jobs-in-uk' => Database\Seeders\WorkFromHomeJobsUkBlogSeeder::class,
        'data-entry-jobs-in-usa' => Database\Seeders\DataEntryJobsUsaBlogSeeder::class,
        'help-desk-technician-jobs-in-usa' => Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/work-from-home-jobs-in-usa');
    }
});

it('replaces the UK foreigners draft threshold, shortage list, care, graduate and transfer visa claims with current gov.uk rules', function () {
    // The draft quotes a £26,200 threshold, a shortage occupation list, an
    // open care route, a two-to-three-year Graduate visa and an Intra-Company
    // Transfer visa. On record: the £41,700 threshold, the ISL and TSL expiry,
    // the 22 July 2025 care closure, B2 English and the 2027 Graduate change.
    $this->seed(Database\Seeders\JobsInUkForForeignersBlogSeeder::class);

    expect(Blog::where('slug', 'jobs-in-uk-for-foreigners')->value('content'))
        ->toContain('<strong>£41,700 a year</strong>')
        ->toContain('<strong>Immigration Salary List</strong>')
        ->toContain('<strong>expire on 31 December 2026</strong>')
        ->toContain('<strong>22 July 2025</strong>')
        ->toContain('<strong>level B2</strong>')
        ->toContain('<strong>18 months if you apply on or after 1 January 2027</strong>')
        ->toContain('replaced the Intra-Company Transfer visa')
        ->toContain('£819 fee up to 3 years')
        ->toContain('£2,530 savings')
        ->toContain('Hong Kong and Taiwan')
        ->toContain('register of licensed sponsors')
        ->toContain('https://uk.indeed.com/q-visa-sponsorship-jobs.html')
        ->not->toContain('starts around £26,200 per year')
        ->not->toContain('indeed.co.uk/jobs');

    $siblings = [
        'warehouse-jobs-uk-visa-sponsorship' => Database\Seeders\WarehouseUkBlogSeeder::class,
        'healthcare-assistant-jobs-in-uk' => Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
        'it-support-jobs-in-uk' => Database\Seeders\ItSupportJobsUkBlogSeeder::class,
        'teaching-jobs-in-uk' => Database\Seeders\TeachingJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/jobs-in-uk-for-foreigners');
    }
});

it('replaces the entry level IT draft pay bands, demand, cybersecurity and certification claims with BLS and CompTIA data', function () {
    // The draft's systems and cyber bands sit below the lowest-paid tenth,
    // it calls demand steady, treats junior cyber as a first job and names
    // A+ without the exam change. On record: BLS May 2025 wages, the 2025-35
    // projections, BLS entry requirements and the V15 A+ launch.
    $this->seed(Database\Seeders\EntryLevelItJobsBlogSeeder::class);

    expect(Blog::where('slug', 'entry-level-it-jobs')->value('content'))
        ->toContain('$40,980')
        ->toContain('$61,860')
        ->toContain('$62,640')
        ->toContain('$75,090')
        ->toContain('$129,180')
        ->toContain('<strong>Computer support specialists: down 3 per cent.</strong>')
        ->toContain("bachelor's degree plus work experience in a related occupation")
        ->toContain('<strong>220-1201 (Core 1) and 220-1202 (Core 2)</strong>')
        ->toContain('<strong>25 March 2025</strong>')
        ->toContain('N10-009')
        ->toContain('SY0-701')
        ->toContain('https://www.indeed.com/q-entry-level-it-jobs.html')
        ->not->toContain('jobs?q=entry+level+it');

    $siblings = [
        'help-desk-technician-jobs-in-usa' => Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class,
        'network-engineer-jobs-in-usa' => Database\Seeders\NetworkEngineerJobsUsaBlogSeeder::class,
        'cybersecurity-analyst-jobs-in-usa' => Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder::class,
        'web-developer-jobs-in-usa' => Database\Seeders\WebDeveloperJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/entry-level-it-jobs');
    }
});

it('replaces the customer service draft demand, pay and technical support claims with BLS data', function () {
    // The draft calls demand steady, folds IT help desk into a low band, sells
    // customer success as a BLS tier and leaves the pay unsourced. On record:
    // BLS May 2025 OEWS wages, the 2025-35 projection and the OOH entry path.
    $this->seed(Database\Seeders\CustomerServiceJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'customer-service-jobs-in-usa')->value('content'))
        ->toContain('$44,770')
        ->toContain('$31,750')
        ->toContain('$63,590')
        ->toContain('<strong>Employment is projected to fall 5 per cent from 2025 to 2035</strong>')
        ->toContain('141,800 jobs')
        ->toContain('289,500 openings')
        ->toContain('computer user support specialists')
        ->toContain('$61,860')
        ->toContain('industry job title, not a BLS occupation')
        ->toContain('high school diploma or equivalent')
        ->toContain('https://www.indeed.com/q-customer-service-representative-jobs.html')
        ->not->toContain('jobs?q=customer+service');

    $siblings = [
        'remote-customer-service-jobs' => Database\Seeders\RemoteCustomerServiceJobsBlogSeeder::class,
        'work-from-home-jobs-in-usa' => Database\Seeders\WorkFromHomeJobsUsaBlogSeeder::class,
        'help-desk-technician-jobs-in-usa' => Database\Seeders\HelpDeskTechnicianJobsUsaBlogSeeder::class,
        'retail-associate-jobs-in-usa' => Database\Seeders\RetailAssociateJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/customer-service-jobs-in-usa');
    }
});

it('replaces the healthcare support draft NHS pay, Care Certificate, qualification and visa claims with official data', function () {
    // The draft quotes 2023-era NHS bands, a 15-standard Care Certificate,
    // NVQ/QCF naming and no visa change. On record: 2026/27 Agenda for Change
    // scales, the 16-standard update, RQF diplomas and the 22 July 2025 closure.
    $this->seed(Database\Seeders\HealthcareSupportJobsUkBlogSeeder::class);

    expect(Blog::where('slug', 'healthcare-support-jobs-in-uk')->value('content'))
        ->toContain('GBP 25,272 (single point)')
        ->toContain('GBP 25,760 &ndash; GBP 27,476')
        ->toContain('GBP 28,392 &ndash; GBP 31,157')
        ->toContain('<strong>16 standards</strong>')
        ->toContain('Level 2 Adult Social Care Certificate qualification')
        ->toContain('Regulated Qualifications Framework (RQF)')
        ->toContain('closed to new applicants from overseas on 22 July 2025')
        ->toContain('22 July 2028')
        ->toContain('enhanced DBS check')
        ->toContain('employer arranges and pays for it')
        ->toContain('6.2 per cent')
        ->toContain('https://uk.indeed.com/q-healthcare-support-worker-jobs.html')
        ->toContain('Diploma in Adult Care (RQF)')
        ->not->toContain('15 core competencies');

    $siblings = [
        'healthcare-assistant-jobs-in-uk' => Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
        'healthcare-jobs-in-the-uk' => Database\Seeders\HealthcareJobsUkBlogSeeder::class,
        'caregiver-jobs-in-uk-with-visa-sponsorship' => Database\Seeders\CaregiverUkBlogSeeder::class,
        'jobs-in-uk-for-foreigners' => Database\Seeders\JobsInUkForForeignersBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/healthcare-support-jobs-in-uk');
    }
});

it('replaces the online teacher draft pay, China market, nationality and TEFL claims with sourced data', function () {
    // The draft quotes a stale $10-25 ESL band, treats the China market as
    // live, ignores nationality rules and calls TEFL accredited. On record:
    // platform-published rates, China's 2021 policy, and BLS tutor pay.
    $this->seed(Database\Seeders\OnlineTeacherJobsWorldwideBlogSeeder::class);

    expect(Blog::where('slug', 'online-teacher-jobs-worldwide')->value('content'))
        ->toContain('$0.17 a minute')
        ->toContain('$10.20 an hour')
        ->toContain('18% to 33%')
        ->toContain('100% of your first trial lesson')
        ->toContain('$43,350 a year')
        ->toContain('<strong>24 July 2021</strong>')
        ->toContain('19 October 2021')
        ->toContain('open to any nationality')
        ->toContain('120-hour certificate')
        ->toContain('no global regulator')
        ->toContain('US state teaching licence')
        ->toContain('https://www.indeed.com/q-online-teacher-jobs.html')
        ->not->toContain('jobs?q=online+teacher');

    $siblings = [
        'teacher-jobs-in-pakistan' => Database\Seeders\TeacherJobsPakistanBlogSeeder::class,
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'teaching-jobs-in-uk' => Database\Seeders\TeachingJobsUkBlogSeeder::class,
        'online-jobs-in-pakistan' => Database\Seeders\OnlineJobsPakistanBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/online-teacher-jobs-worldwide');
    }
});

it('replaces the intelligence analyst draft pay, clearance and citizenship claims with official data', function () {
    // The draft gives unsourced bands, treats the role as broadly open and
    // understates the clearance. On record: 2026 GS pay, the BLS proxy, the
    // TS/SCI norm, and the citizenship rule under Executive Order 12968.
    $this->seed(Database\Seeders\IntelligenceAnalystJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'intelligence-analyst-jobs-in-usa')->value('content'))
        ->toContain('no BLS "intelligence analyst" occupation')
        ->toContain('$93,790')
        ->toContain('$160,540')
        ->toContain('$43,106')
        ->toContain('$90,925')
        ->toContain('locality pay adds 17 to 34 per cent')
        ->toContain('A clearance requires US citizenship')
        ->toContain('Executive Order 12968')
        ->toContain('Top Secret plus Sensitive Compartmented Information (TS/SCI)')
        ->toContain('18 member organisations')
        ->toContain('https://www.indeed.com/q-intelligence-analyst-jobs.html')
        ->not->toContain('jobs?q=intelligence+analyst');

    $siblings = [
        'cybersecurity-analyst-jobs-in-usa' => Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder::class,
        'federal-police-jobs-in-usa' => Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        'police-officer-jobs-in-usa' => Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        'data-scientist-jobs-in-usa' => Database\Seeders\DataScientistJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/intelligence-analyst-jobs-in-usa');
    }
});

it('leads the online data entry guide with the BLS decline, real pay and the FTC scam rule', function () {
    // The draft frames data entry as a stable path and overstates transcription
    // pay. On record: the BLS 2024-34 decline, the OEWS percentiles, the
    // platforms' per-audio-minute rates and the FTC's pay-to-work rule.
    $this->seed(Database\Seeders\OnlineDataEntryJobsBlogSeeder::class);

    expect(Blog::where('slug', 'online-data-entry-jobs')->value('content'))
        ->toContain('down 25.9%')
        ->toContain('down 36.1%')
        ->toContain('$41,340')
        ->toContain('$15.00')
        ->toContain('$28.26')
        ->toContain('per audio minute')
        ->toContain('$0.40')
        ->toContain('four working hours')
        ->toContain('never ask you to pay to get a job')
        ->toContain('reshipping')
        ->toContain('reportfraud.ftc.gov')
        ->toContain('https://www.indeed.com/q-online-data-entry-jobs.html')
        ->not->toContain('jobs?q=online+data+entry');

    $siblings = [
        'data-entry-jobs-in-usa' => Database\Seeders\DataEntryJobsUsaBlogSeeder::class,
        'remote-data-entry-jobs' => Database\Seeders\RemoteDataEntryJobsBlogSeeder::class,
        'data-entry-jobs-in-pakistan' => Database\Seeders\DataEntryJobsPakistanBlogSeeder::class,
        'work-from-home-jobs-in-usa' => Database\Seeders\WorkFromHomeJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/online-data-entry-jobs');
    }
});

it('anchors UK marketing pay on the two official codes instead of one average', function () {
    // The draft gives one "average marketing salary". ONS splits the field
    // across an associate-professional code and a director code whose medians
    // are nearly three times apart, and the National Careers Service anchors the
    // middle. Free platform certs are separated from the paid CIM ladder.
    $this->seed(Database\Seeders\MarketingJobsUkBlogSeeder::class);

    expect(Blog::where('slug', 'marketing-jobs-in-uk')->value('content'))
        ->toContain('SOC 3554')
        ->toContain('SOC 1132')
        ->toContain('GBP 32,760')
        ->toContain('GBP 89,700')
        ->toContain('National Careers Service')
        ->toContain('GBP 23,000')
        ->toContain('GBP 50,000')
        ->toContain('GBP 30,000')
        ->toContain('GBP 65,000')
        ->toContain('Royal Charter')
        ->toContain('Google Skillshop')
        ->toContain('HubSpot Academy')
        ->toContain('GBP 162,792')
        ->toContain('https://uk.indeed.com/q-marketing-jobs.html')
        ->not->toContain('jobs?q=marketing');

    $siblings = [
        'digital-marketing-jobs-in-usa' => Database\Seeders\DigitalMarketingJobsUsaBlogSeeder::class,
        'social-media-manager-jobs-in-usa' => Database\Seeders\SocialMediaManagerJobsUsaBlogSeeder::class,
        'content-writer-jobs-in-usa' => Database\Seeders\ContentWriterJobsUsaBlogSeeder::class,
        'graphic-designer-jobs-in-usa' => Database\Seeders\GraphicDesignerJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/marketing-jobs-in-uk');
    }
});

it('lifts the maintenance technician ceiling to the real BLS percentiles', function () {
    // The draft caps experienced pay at $55,000; BLS puts the 75th percentile
    // at $62,620 and the 90th above $77,180. Growth is average, not strong, and
    // "OSHA certified" is not a thing.
    $this->seed(Database\Seeders\MaintenanceTechnicianJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'maintenance-technician-jobs-in-usa')->value('content'))
        ->toContain('$35,350')
        ->toContain('$49,590')
        ->toContain('$62,620')
        ->toContain('$77,180')
        ->toContain('4% from 2025 to 2035')
        ->toContain('148,700')
        ->toContain('95% of those openings')
        ->toContain('$64,520')
        ->toContain('$61,010')
        ->toContain('$60,850')
        ->toContain('Section 608')
        ->toContain('does not expire')
        ->toContain('Type I')
        ->toContain('Universal')
        ->toContain('OSHA does not certify individuals')
        ->toContain('10-hour and 30-hour completion cards')
        ->toContain('https://www.indeed.com/q-maintenance-technician-jobs.html')
        ->not->toContain('jobs?q=maintenance');

    $siblings = [
        'carpenter-jobs-in-usa' => Database\Seeders\CarpenterJobsUsaBlogSeeder::class,
        'forklift-operator-jobs-in-usa' => Database\Seeders\ForkliftOperatorJobsUsaBlogSeeder::class,
        'construction-jobs-in-usa-for-foreigners' => Database\Seeders\ConstructionUsaBlogSeeder::class,
        'unskilled-jobs-in-usa-for-foreigners' => Database\Seeders\UnskilledJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/maintenance-technician-jobs-in-usa');
    }
});

it('corrects the Saudi truck licence conversion myth and the no-minimum-wage reality', function () {
    // The draft says an overseas driver can convert a licence on arrival; for
    // India, Pakistan, Bangladesh and the Philippines that is false. It also
    // treats a quoted salary as a floor when no expat minimum wage exists.
    $this->seed(Database\Seeders\HeavyTruckDriverJobsSaudiBlogSeeder::class);

    expect(Blog::where('slug', 'heavy-truck-driver-jobs-in-saudi-arabia')->value('content'))
        ->toContain('cannot simply convert')
        ->toContain('public / heavy-transport licence')
        ->toContain('no statutory minimum wage for expatriate private-sector workers')
        ->toContain('SAR 4,000')
        ->toContain('Nitaqat')
        ->toContain('Wage Protection System')
        ->toContain('reserved for Saudi nationals since January 2021')
        ->toContain('2026-2028')
        ->toContain('Labour Reform Initiative of 14 March 2021')
        ->toContain('Musaned is for domestic workers only')
        ->toContain('BEOE')
        ->toContain('https://sa.indeed.com/q-heavy-truck-driver-jobs.html')
        ->not->toContain('jobs?q=heavy+truck+driver');

    $siblings = [
        'driver-jobs-in-saudi-arabia-for-foreigners' => Database\Seeders\DriverJobsSaudiBlogSeeder::class,
        'construction-jobs-in-saudi-arabia-with-visa-sponsorship' => Database\Seeders\ConstructionJobsSaudiBlogSeeder::class,
        'mechanic-jobs-in-saudi-arabia' => Database\Seeders\MechanicJobsSaudiBlogSeeder::class,
        'no-experience-jobs-in-saudi-arabia' => Database\Seeders\NoExperienceJobsSaudiBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/heavy-truck-driver-jobs-in-saudi-arabia');
    }
});

it('frames the Canada foreign worker levels plan, low-wage rules and Job Bank pay', function () {
    // The draft quotes the 60,000 TFWP target as if it were Canada's whole
    // intake and never mentions the rules that gate a low-wage LMIA. On record:
    // the 2026-2028 levels plan, the ESDC low-wage rules and Job Bank wages.
    $this->seed(Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class);

    expect(Blog::where('slug', 'jobs-in-canada-for-foreign-workers')->value('content'))
        ->toContain('target is 60,000 for 2026')
        ->toContain('170,000')
        ->toContain('230,000 new worker arrivals in 2026')
        ->toContain('In-Canada Workers Initiative')
        ->toContain('<strong>1 April 2026</strong>')
        ->toContain('<strong>6% or more</strong>')
        ->toContain('valid for only six months')
        ->toContain('$43.27')
        ->toContain('https://ca.indeed.com/Foreign-Worker-Canada-jobs')
        ->not->toContain('jobs?q=foreign');

    $siblings = [
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'farm-worker-jobs-in-canada' => Database\Seeders\FarmWorkerJobsCanadaBlogSeeder::class,
        'welder-jobs-in-canada' => Database\Seeders\WelderJobsCanadaBlogSeeder::class,
        'heavy-equipment-operator-jobs-in-canada' => Database\Seeders\HeavyEquipmentOperatorJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/jobs-in-canada-for-foreign-workers');
    }
});

it('corrects the Pakistan call centre pay floor, million-rupee claim, export figure and equipment rule', function () {
    // The draft's PKR 30,000 floor is below the minimum wage, its million-rupee
    // month is an outlier, it conflates the call-centre export figure with total
    // IT exports and blames Mac-incompatible CRMs. On record: the 2025-26
    // minimum wage, SBP export data, ERI pay data and the real dialer constraint.
    $this->seed(Database\Seeders\CallCenterJobsPakistanBlogSeeder::class);

    expect(Blog::where('slug', 'call-center-jobs-in-pakistan')->value('content'))
        ->toContain('<strong>PKR 40,000 a month</strong>')
        ->toContain('PKR 37,000 in 2024-25')
        ->toContain('$328 million')
        ->toContain('$3.8 billion')
        ->toContain('PKR 405,600')
        ->toContain('Salesforce and Zendesk run in a browser on a Mac')
        ->toContain('Windows-only')
        ->toContain('25 Mbps')
        ->toContain('Bureau of Emigration')
        ->toContain('https://pk.indeed.com/q-pakistan-call-center-jobs.html')
        ->not->toContain('jobs?q=pakistan+call');

    $siblings = [
        'virtual-assistant-jobs-in-pakistan' => Database\Seeders\VirtualAssistantJobsPakistanBlogSeeder::class,
        'data-entry-jobs-in-pakistan' => Database\Seeders\DataEntryJobsPakistanBlogSeeder::class,
        'online-jobs-in-pakistan' => Database\Seeders\OnlineJobsPakistanBlogSeeder::class,
        'customer-service-jobs-in-usa' => Database\Seeders\CustomerServiceJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/call-center-jobs-in-pakistan');
    }
});

it('corrects the UK office assistant below-minimum pay, the visa threshold and the skill bar', function () {
    // The draft quotes pay below the minimum wage, names the old GBP 38,700
    // threshold and says the role fails on RQF Level 3. On record: the April
    // 2026 National Living Wage, the GBP 41,700 threshold and the RQF Level 6
    // skill bar from 22 July 2025.
    $this->seed(Database\Seeders\OfficeAssistantJobsUkBlogSeeder::class);

    expect(Blog::where('slug', 'office-assistant-jobs-in-uk')->value('content'))
        ->toContain('<strong>GBP 12.71 an hour from 1 April 2026</strong>')
        ->toContain('GBP 24,800')
        ->toContain('GBP 41,700')
        ->toContain('<strong>RQF Level 6</strong>')
        ->toContain('GBP 1,270 held for 28 consecutive days')
        ->toContain('1 January 2027')
        ->toContain('below the legal minimum')
        ->toContain('https://uk.indeed.com/Office-Assistant-jobs')
        ->not->toContain('jobs?q=office');

    $siblings = [
        'jobs-in-uk-for-foreigners' => Database\Seeders\JobsInUkForForeignersBlogSeeder::class,
        'administrative-assistant-jobs-in-usa' => Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder::class,
        'office-assistant-jobs-in-australia' => Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
        'work-from-home-jobs-in-uk' => Database\Seeders\WorkFromHomeJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/office-assistant-jobs-in-uk');
    }
});

it('anchors Australian aged care pay on the award and frames the ACILA visa floor', function () {
    // The draft's entry pay predates the 1 October 2025 work value increase, it
    // calls the visa by its old name, and it treats the $51,222 labour-agreement
    // floor as a market wage. On record: the Aged Care Award rate, the Skills in
    // Demand visa, ANZSCO 423313 and the current Core Skills Income Threshold.
    $this->seed(Database\Seeders\PersonalCareAssistantJobsAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'personal-care-assistant-jobs-in-australia')->value('content'))
        ->toContain('Skills in Demand')
        ->toContain('subclass 482')
        ->toContain('423313')
        ->toContain('$51,222')
        ->toContain('$79,499')
        ->toContain('1 October 2025')
        ->toContain('$36.23')
        ->toContain('NDIS Worker Screening')
        ->toContain('$15,900')
        ->toContain('subclass 186')
        ->toContain('two years')
        ->toContain('https://au.indeed.com/q-sponsorship-visa,-personal-care-assistant-jobs.html')
        ->not->toContain('jobs?q=personal');

    $siblings = [
        'visa-sponsorship-jobs-in-australia' => Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder::class,
        'medical-receptionist-jobs-in-australia' => Database\Seeders\MedicalReceptionistJobsAustraliaBlogSeeder::class,
        'receptionist-jobs-in-australia' => Database\Seeders\ReceptionistJobsAustraliaBlogSeeder::class,
        'healthcare-support-jobs-in-uk' => Database\Seeders\HealthcareSupportJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/personal-care-assistant-jobs-in-australia');
    }
});

it('updates US physical therapist pay, the compact count and the VisaScreen issuer', function () {
    // The draft quotes stale median pay, understates the PT Compact and says
    // FCCPT issues VisaScreen. On record: the May 2025 BLS median, the 2026
    // compact count, and that CGFNS issues VisaScreen while FCCPT issues a Type I.
    $this->seed(Database\Seeders\PhysicalTherapistJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'physical-therapist-jobs-in-usa')->value('content'))
        ->toContain('$102,760')
        ->toContain('$49.40')
        ->toContain('$132,500')
        ->toContain('12%')
        ->toContain('13,400')
        ->toContain('41 jurisdictions')
        ->toContain('VisaScreen')
        ->toContain('CGFNS')
        ->toContain('Type I')
        ->toContain('Schedule A')
        ->toContain('NPTE')
        ->toContain('https://www.indeed.com/q-physical-therapist-jobs.html')
        ->not->toContain('jobs?q=physical');

    $siblings = [
        'registered-nurse-jobs-in-usa' => Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class,
        'medical-assistant-jobs-in-usa' => Database\Seeders\MedicalAssistantJobsUsaBlogSeeder::class,
        'school-nurse-jobs-in-usa' => Database\Seeders\SchoolNurseJobsUsaBlogSeeder::class,
        'healthcare-assistant-jobs-in-uk' => Database\Seeders\HealthcareAssistantJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/physical-therapist-jobs-in-usa');
    }
});

it('anchors Canadian OT pay on Job Bank and folds in the 2026 Express Entry change', function () {
    // The draft gives a pay range with no source, inflates the top end past Job
    // Bank's high wage, and misses the February 2026 experience change. On
    // record: the Job Bank median, the NOTCE/SEAS route and the IRCC update.
    $this->seed(Database\Seeders\OccupationalTherapistJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'occupational-therapist-jobs-in-canada')->value('content'))
        ->toContain('$46.00')
        ->toContain('$36.17')
        ->toContain('$55.00')
        ->toContain('19 November 2025')
        ->toContain('18 February 2026')
        ->toContain('one year of qualifying work experience')
        ->toContain('Substantial Equivalency Assessment System')
        ->toContain('NOTCE')
        ->toContain('except Quebec')
        ->toContain('30 September 2026')
        ->toContain('https://ca.indeed.com/q-occupational-therapist-jobs.html')
        ->not->toContain('jobs?q=occupational');

    $siblings = [
        'dental-assistant-jobs-in-canada' => Database\Seeders\DentalAssistantJobsCanadaBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'recruiter-jobs-in-canada' => Database\Seeders\RecruiterJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/occupational-therapist-jobs-in-canada');
    }
});

it('fixes the Japan ESL draft visa name, degree rule, CoE timing, EPI rank and pay with official sources', function () {
    // The draft shortens the visa name, stretches the ten-year route to
    // language teaching, quotes 4-8 weeks for the CoE, says address
    // registration activates the card, and misses the October 2026 fee rise.
    $this->seed(Database\Seeders\EslTeacherJobsJapanBlogSeeder::class);

    expect(Blog::where('slug', 'how-to-get-an-esl-teaching-job-in-japan')->value('content'))
        ->toContain('Engineer/Specialist in Humanities/International Services')
        ->toContain('three years of relevant experience, waived for university graduates')
        ->toContain('Twelve or more years of education in the language you teach')
        ->toContain('one to three months')
        ->toContain('17 March 2023')
        ->toContain('14 days')
        ->toContain('does not "activate" the card')
        ->toContain('<strong>April</strong>, not September')
        ->toContain('96th of 123')
        ->toContain('4.02 million yen')
        ->toContain('4.32 million yen')
        ->toContain('1 October 2026')
        ->toContain('33,000 yen at the counter, or 27,000 yen online')
        ->toContain('https://jp.indeed.com/q-english-teacher-jobs.html')
        ->not->toContain('4&ndash;8 weeks')
        ->not->toContain('$1,500');

    $siblings = [
        'online-teacher-jobs-worldwide' => Database\Seeders\OnlineTeacherJobsWorldwideBlogSeeder::class,
        'teaching-jobs-in-uk' => Database\Seeders\TeachingJobsUkBlogSeeder::class,
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'teacher-jobs-in-pakistan' => Database\Seeders\TeacherJobsPakistanBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-get-an-esl-teaching-job-in-japan');
    }
});

it('fixes the Canada correctional officer draft eligibility, training, timeline and pay claims with official sources', function () {
    // The draft turns one employer's rules into national ones, misnames
    // Ontario's training, understates CSC's online stages and gives an
    // unsourced 6-9 month timeline.
    $this->seed(Database\Seeders\CorrectionalOfficerJobsCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'how-to-become-a-correctional-officer-in-canada')->value('content'))
        ->toContain('$28.85')
        ->toContain('$36.15')
        ->toContain('$46.15')
        ->toContain('19 November 2025')
        ->toContain('80 hours of learning over four weeks')
        ->toContain('$400 a week, up to $5,600')
        ->toContain('Corrections Foundational Training for Correctional Officers (CFT-CO)')
        ->toContain('not "COTA"')
        ->toContain('proof of eligibility to work in Canada')
        ->toContain('$77,510 at step 1 to $97,266 at step 5')
        ->toContain('$32.15 an hour')
        ->toContain('COPAT')
        ->toContain('run 5 km')
        ->toContain('up to 12 months')
        ->toContain('https://ca.indeed.com/q-correctional-officer-jobs.html')
        ->not->toContain('30&ndash;40 hours')
        ->not->toContain('17+ weeks');

    $siblings = [
        'police-officer-jobs-in-usa' => Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        'federal-police-jobs-in-usa' => Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-become-a-correctional-officer-in-canada');
    }
});

it('fixes the Australia sales representative draft pay, course, commission, licence and sponsorship claims with official sources', function () {
    // The draft prices the job from job boards, recommends a superseded
    // course, treats clawbacks and commission-only pay as routine, uses the
    // wrong NSW real estate credential and calls sponsorship "uncommon".
    $this->seed(Database\Seeders\SalesRepresentativeJobsAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'how-to-become-a-sales-representative-in-australia')->value('content'))
        ->toContain('$1,692 a week')
        ->toContain('$87,984')
        ->toContain('May 2025')
        ->toContain('23.5%')
        ->toContain('SIR30316 Certificate III in Business to Business Sales')
        ->toContain('superseded on 18 October 2020')
        ->toContain('$1,122.80 a week')
        ->toContain('award or enterprise agreement allows it')
        ->toContain('<strong>written agreement</strong>')
        ->toContain('sham contracting')
        ->toContain('Assistant Agent certificate of registration')
        ->toContain('not on the Core Skills Occupation List')
        ->toContain('225411')
        ->toContain('https://au.indeed.com/q-sales-representative-jobs.html')
        ->not->toContain('$79,500')
        ->not->toContain('$146,000');

    $siblings = [
        'sales-jobs-in-australia' => Database\Seeders\SalesJobsAustraliaBlogSeeder::class,
        'no-experience-jobs-in-australia' => Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
        'visa-sponsorship-jobs-in-australia' => Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder::class,
        'office-assistant-jobs-in-australia' => Database\Seeders\OfficeAssistantJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-become-a-sales-representative-in-australia');
    }
});

it('fixes the UAE logistics driver draft licence, exchange, visa and salary claims with official sources', function () {
    $this->seed(Database\Seeders\LogisticsDriverJobsUaeBlogSeeder::class);

    $content = Blog::where('slug', 'how-to-get-a-logistics-driver-job-in-the-uae')->value('content');

    expect($content)->toContain('licences from Pakistan, India and the Philippines cannot be exchanged')
        ->toContain('The RTA does not use those numbers')
        ->toContain('1,010 to 1,050')
        ->toContain('There is no 30-day grace period')
        ->toContain('UAE law strictly prohibits working while holding a visit or tourist visa')
        ->toContain('The UAE Labour Law sets no minimum salary')
        ->toContain('the employer is prohibited from charging the worker for recruitment and employment fees and costs')
        ->toContain('at least 21 and no more than 55')
        ->toContain('6:00 AM to 10:00 PM')
        ->toContain('https://ae.indeed.com/q-logistics-driver-jobs.html')
        ->not->toContain('Category 3 (LMV)')
        ->not->toContain('950')
        ->not->toContain('1&ndash;2 hours');

    $siblings = [
        'driver-jobs-in-saudi-arabia-for-foreigners' => Database\Seeders\DriverJobsSaudiBlogSeeder::class,
        'heavy-truck-driver-jobs-in-saudi-arabia' => Database\Seeders\HeavyTruckDriverJobsSaudiBlogSeeder::class,
        'security-guard-jobs-in-uae' => Database\Seeders\SecurityGuardJobsUaeBlogSeeder::class,
        'delivery-driver-jobs-in-uk' => Database\Seeders\DeliveryDriverJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-get-a-logistics-driver-job-in-the-uae');
    }
});

it('fixes the remote customer service draft growth, pay, experience and hiring claims with official sources', function () {
    $this->seed(Database\Seeders\RemoteCustomerServiceNoExperienceBlogSeeder::class);

    $content = Blog::where('slug', 'how-to-get-a-remote-customer-service-job-with-no-experience')->value('content');

    expect($content)->toContain('The BLS projects the opposite')
        ->toContain('decline 5 percent from 2025 to 2035')
        ->toContain('About 289,500')
        ->toContain('$21.53 ($44,770 a year)')
        ->toContain('$13.00 to $16.35')
        ->toContain('six months or more')
        ->toContain('Alaska, California, Hawaii, Illinois or Montana')
        ->toContain('will never ask you to pay to get a job')
        ->toContain('https://www.indeed.com/q-remote-customer-service-no-experience-jobs.html')
        ->not->toContain('$14&ndash;$18')
        ->not->toContain('$19&ndash;$26')
        ->not->toContain('double-digit growth ahead.</strong>');

    $siblings = [
        'remote-customer-service-jobs' => Database\Seeders\RemoteCustomerServiceJobsBlogSeeder::class,
        'customer-service-jobs-in-usa' => Database\Seeders\CustomerServiceJobsUsaBlogSeeder::class,
        'work-from-home-jobs-in-usa' => Database\Seeders\WorkFromHomeJobsUsaBlogSeeder::class,
        'remote-jobs-in-usa' => Database\Seeders\RemoteJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-get-a-remote-customer-service-job-with-no-experience');
    }
});

it('fixes the Australia auto mechanic draft apprenticeship, entry, pay, licence and portal claims with official sources', function () {
    $this->seed(Database\Seeders\AutoMechanicJobsAustraliaBlogSeeder::class);

    $content = Blog::where('slug', 'how-to-become-an-auto-mechanic-in-australia')->value('content');

    expect($content)->toContain('AUR30620 Certificate III in Light Vehicle Mechanical Technology')
        ->toContain('36 units: 20 core and 16 elective')
        ->toContain('<strong>no entry requirements</strong>')
        ->toContain('48 months nominal term')
        ->toContain('at least three years')
        ->toContain('$1,405 a week')
        ->toContain('$1,119.10 a week')
        ->toContain('$895.28 a week')
        ->toContain('motor vehicle tradesperson certificate')
        ->toContain('automotive air conditioning licence (AAC02)')
        ->toContain('Trades Recognition Australia')
        ->toContain('apprenticeships.gov.au')
        ->toContain('https://au.indeed.com/q-motor-mechanic-jobs.html')
        ->not->toContain('Australian Apprenticeships Pathways')
        ->not->toContain('$58,600')
        ->not->toContain('$88,600');

    $siblings = [
        'plumber-jobs-in-australia' => Database\Seeders\PlumberJobsAustraliaBlogSeeder::class,
        'construction-worker-jobs-in-australia' => Database\Seeders\ConstructionWorkerJobsAustraliaBlogSeeder::class,
        'visa-sponsorship-jobs-in-australia' => Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder::class,
        'no-experience-jobs-in-australia' => Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-become-an-auto-mechanic-in-australia');
    }
});

it('fixes the entry level healthcare draft projections, training, employer and survey claims with official sources', function () {
    $this->seed(Database\Seeders\EntryLevelHealthcareJobsBlogSeeder::class);

    $content = Blog::where('slug', 'entry-level-healthcare-jobs')->value('content');

    expect($content)->toContain('from 2025 to 2035')
        ->toContain('1.9 million openings a year')
        ->toContain('<strong>18%</strong>')
        ->toContain('$51,140 ($24.59/hr)')
        ->toContain('at least 75 clock hours of training, including at least 16 hours of supervised practical training')
        ->toContain('42 CFR 483.152')
        ->toContain('approved medical assisting program and a clinical externship')
        ->toContain('$21.96 to $29.02 an hour')
        ->toContain('on-the-job training was required for 86.5 percent</strong>')
        ->toContain('States may require that phlebotomists')
        ->toContain('https://www.indeed.com/q-entry-level-healthcare-jobs.html')
        ->not->toContain('2024-34 Projected Growth')
        ->not->toContain('utm_source=chatgpt.com')
        ->not->toContain('search-jobs?k=');

    $siblings = [
        'medical-assistant-jobs-in-usa' => Database\Seeders\MedicalAssistantJobsUsaBlogSeeder::class,
        'registered-nurse-jobs-in-usa' => Database\Seeders\RegisteredNurseJobsUsaBlogSeeder::class,
        'nurse-jobs-in-the-us' => Database\Seeders\NurseJobsUsBlogSeeder::class,
        'physical-therapist-jobs-in-usa' => Database\Seeders\PhysicalTherapistJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/entry-level-healthcare-jobs');
    }
});

it('fixes the Canada preschool teacher draft qualification, licensing, pay, employer and immigration claims with official sources', function () {
    $this->seed(Database\Seeders\PreschoolTeacherJobsCanadaBlogSeeder::class);

    $content = Blog::where('slug', 'preschool-teacher-jobs-in-canada')->value('content');

    expect($content)->toContain('NOC 42202')
        ->toContain('Licensing is required in Ontario and certification is required in British Columbia for early childhood educators.')
        ->toContain('child care experience are required')
        ->toContain('<strong>Level 1</strong>')
        ->toContain('<strong>$22.30/hr</strong>')
        ->toContain('<strong>$25.86 an hour</strong>')
        ->toContain('Over 100,000 new job openings are anticipated between now and 2031.')
        ->toContain('strong risk of shortage')
        ->toContain('Brampton, Mississauga, Caledon and Toronto')
        ->toContain('across the United States and Canada')
        ->toContain('Express Entry education occupations category')
        ->toContain('vulnerable sector check')
        ->toContain('https://ca.indeed.com/q-early-childhood-educator-jobs.html')
        ->not->toContain('ymcagta.org/careers')
        ->not->toContain('utm_source=chatgpt.com')
        ->not->toContain('10-a-day');

    $siblings = [
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'dental-assistant-jobs-in-canada' => Database\Seeders\DentalAssistantJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/preschool-teacher-jobs-in-canada');
    }
});

it('fixes the Australia foreign worker draft English, income, permanent residence and visa route claims with official sources', function () {
    $this->seed(Database\Seeders\ForeignWorkerJobsAustraliaBlogSeeder::class);

    $content = Blog::where('slug', 'how-foreign-workers-can-get-a-job-in-australia')->value('content');

    expect($content)->toContain('AUD 79,423')
        ->toContain('AUD 146,576')
        ->toContain('<strong>at least 1 year</strong>')
        ->toContain('at least 6 in each of the four test components</strong>')
        ->toContain('2 years of eligible sponsored employment in the 3 years</strong>')
        ->toContain('Permanent Residence (Skilled Regional) visa (subclass 191)')
        ->toContain('<strong>65 points or more</strong>')
        ->toContain('<strong>48 hours a fortnight</strong>')
        ->toContain('must enter a <strong>ballot</strong>')
        ->toContain('Pacific Australia Labour Mobility (PALM) scheme')
        ->toContain('https://au.indeed.com/q-482-visa-sponsorship-jobs.html')
        ->not->toContain('17 days')
        ->not->toContain('IELTS score of 5.0')
        ->not->toContain('ifmosawork.com')
        ->not->toContain('sponsorhire.com');

    $siblings = [
        'visa-sponsorship-jobs-in-australia' => Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder::class,
        'no-experience-jobs-in-australia' => Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'jobs-in-uk-for-foreigners' => Database\Seeders\JobsInUkForForeignersBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-foreign-workers-can-get-a-job-in-australia');
    }
});

it('fixes the US tutor draft pay, platform, franchise, requirement and tax claims with official sources', function () {
    $this->seed(Database\Seeders\TutorJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', 'tutor-jobs-in-usa')->value('content');

    expect($content)->toContain('<strong>$20.84 an hour</strong>')
        ->toContain('0%, little or no change')
        ->toContain('<strong>most tutors work part time</strong>')
        ->toContain('<strong>25% platform fee</strong>')
        ->toContain('possess a valid Social Security Number')
        ->toContain('<strong>independent contractors</strong>')
        ->toContain('Our tutors have degrees from four-year colleges, plus either state or Huntington certification.')
        ->toContain('your employer will not be Kumon')
        ->toContain('<strong>self-employment tax rate is 15.3%</strong>')
        ->toContain('huntingtonhelps.com/careers')
        ->toContain('https://www.indeed.com/q-tutor-jobs.html')
        ->not->toContain('up to four students')
        ->not->toContain('careerplug.com')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'online-teacher-jobs-worldwide' => Database\Seeders\OnlineTeacherJobsWorldwideBlogSeeder::class,
        'work-from-home-jobs-in-usa' => Database\Seeders\WorkFromHomeJobsUsaBlogSeeder::class,
        'how-to-get-an-esl-teaching-job-in-japan' => Database\Seeders\EslTeacherJobsJapanBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/tutor-jobs-in-usa');
    }
});

it('fixes the Australia education assistant draft qualification, pathway, register and state title claims with official sources', function () {
    $this->seed(Database\Seeders\EducationAssistantJobsAustraliaBlogSeeder::class);

    $content = Blog::where('slug', 'education-assistant-jobs-in-australia')->value('content');

    expect($content)->toContain('<strong>$34.75 &ndash; $39.05</strong>')
        ->toContain('<strong>$36.87 &ndash; $41.37</strong>')
        ->toContain('<strong>$43.28</strong>')
        ->toContain('are listed as desirable, so you can apply without the certificate')
        ->toContain('engaged only through strategic School Workforce initiatives')
        ->toContain('but not as an SSO under this program')
        ->toContain('Registrations for the 2026 program have closed')
        ->toContain('full-time and part-time, fixed-term and permanent positions')
        ->toContain('<strong>blue card</strong>')
        ->toContain('<strong>school terms only receive a 16% loading</strong>')
        ->toContain('Certificate III in School Based Education Support (CHC30221)')
        ->toContain('https://www.vic.gov.au/school-jobs')
        ->toContain('https://au.indeed.com/q-education-assistant-jobs.html')
        ->not->toContain('Education Support Officer | Various employers')
        ->not->toContain('full-time, part-time, and casual')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'personal-care-assistant-jobs-in-australia' => Database\Seeders\PersonalCareAssistantJobsAustraliaBlogSeeder::class,
        'no-experience-jobs-in-australia' => Database\Seeders\NoExperienceJobsAustraliaBlogSeeder::class,
        'how-foreign-workers-can-get-a-job-in-australia' => Database\Seeders\ForeignWorkerJobsAustraliaBlogSeeder::class,
        'preschool-teacher-jobs-in-canada' => Database\Seeders\PreschoolTeacherJobsCanadaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/education-assistant-jobs-in-australia');
    }
});

it('fixes the UK school administrator draft listing, job portal, sponsorship and entry route claims with official sources', function () {
    $this->seed(Database\Seeders\SchoolAdministratorJobsUkBlogSeeder::class);

    $content = Blog::where('slug', 'school-administrator-jobs-in-uk')->value('content');

    expect($content)->toContain('<strong>£22,000 &ndash; £28,000</strong>')
        ->toContain('<strong>£26,000 &ndash; £52,000</strong>')
        ->toContain('Actual pay will depend on the number of hours you work over a year.')
        ->toContain('<strong>£11,651.76</strong>')
        ->toContain('<strong>England only</strong>')
        ->toContain('https://www.gov.uk/find-a-job')
        ->toContain('<strong>school secretaries (SOC code 4213) are "Ineligible"</strong>')
        ->toContain('<strong>"Visas cannot be sponsored"</strong>')
        ->toContain('School business professional (ST0575)')
        ->toContain('<strong>4 or 5 GCSEs at grades 9 to 4 (A* to C)</strong>')
        ->toContain('including children\'s barred list information')
        ->toContain('https://teaching-vacancies.service.gov.uk/jobs?keyword=school+administrator')
        ->not->toContain('17,506')
        ->not->toContain('Work for government')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'office-assistant-jobs-in-uk' => Database\Seeders\OfficeAssistantJobsUkBlogSeeder::class,
        'teaching-jobs-in-uk' => Database\Seeders\TeachingJobsUkBlogSeeder::class,
        'jobs-in-uk-for-foreigners' => Database\Seeders\JobsInUkForForeignersBlogSeeder::class,
        'education-assistant-jobs-in-australia' => Database\Seeders\EducationAssistantJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/school-administrator-jobs-in-uk');
    }
});

it('fixes the US educational support draft Title I, supervision, employer and franchise claims with official sources', function () {
    $this->seed(Database\Seeders\EducationalSupportJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', 'educational-support-jobs-in-usa')->value('content');

    expect($content)->toContain('<strong>1,463,000 jobs in 2025</strong>')
        ->toContain('<strong>176,200 openings a year</strong>')
        ->toContain('<strong>median annual wage of $36,780</strong>')
        ->toContain('<strong>a high school diploma or its recognized equivalent</strong>')
        ->toContain('work with or under the guidance of a licensed teacher')
        ->toContain('Most states require teacher assistants who work with special-needs students to pass a skills test.')
        ->toContain('your employer is the KIPP region, not the KIPP Foundation.')
        ->toContain('<strong>salary of $42,000</strong>')
        ->toContain('<strong>90 selected-response questions</strong>')
        ->toContain('the respective Franchise Owner is the employer at each school')
        ->toContain('665+ schools across 37 states and Washington, DC')
        ->toContain('https://www.indeed.com/q-paraprofessional-jobs.html')
        ->not->toContain('3,000 Kumon')
        ->not->toContain('Teacher &amp; Staff Opportunities')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'teacher-jobs-in-usa' => Database\Seeders\TeacherJobsUsaBlogSeeder::class,
        'tutor-jobs-in-usa' => Database\Seeders\TutorJobsUsaBlogSeeder::class,
        'education-assistant-jobs-in-australia' => Database\Seeders\EducationAssistantJobsAustraliaBlogSeeder::class,
        'school-administrator-jobs-in-uk' => Database\Seeders\SchoolAdministratorJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/educational-support-jobs-in-usa');
    }
});

it('fixes the Australia government security draft Border Force, ASIO, ASD and graduate pathway claims with official sources', function () {
    $this->seed(Database\Seeders\GovernmentSecurityJobsAustraliaBlogSeeder::class);

    $content = Blog::where('slug', 'government-security-jobs-in-australia')->value('content');

    expect($content)->toContain('<strong>Border Force Officer Recruit Trainee (BFORT) program</strong>')
        ->toContain('<strong>Assessments, Security Force &amp; T4</strong>')
        ->toContain('Assessed as suitable to hold and maintain a TOP SECRET-Privileged Access security clearance.')
        ->toContain('<strong>Organisational Suitability Assessment</strong>')
        ->toContain('You must indicate the Department of Home Affairs as a preferred employer.')
        ->toContain('All staff must also hold a minimum Baseline level Commonwealth Security Clearance.')
        ->toContain('<strong>$82,499.88</strong>')
        ->toContain('<strong>Canberra, Perth, Exmouth, Geraldton and Pine Gap</strong>')
        ->toContain('https://www.apsjobs.gov.au/')
        ->not->toContain('Assistant Border Force Officer')
        ->not->toContain('Security Director')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'federal-police-jobs-in-usa' => Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        'cybersecurity-analyst-jobs-in-usa' => Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder::class,
        'how-to-become-a-correctional-officer-in-canada' => Database\Seeders\CorrectionalOfficerJobsCanadaBlogSeeder::class,
        'security-specialist-jobs-in-uae' => Database\Seeders\SecuritySpecialistJobsUaeBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/government-security-jobs-in-australia');
    }
});

it('fixes the US emergency dispatcher draft experience, posting date, answer time and entry rule claims with official sources', function () {
    $this->seed(Database\Seeders\EmergencyDispatcherJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', 'emergency-dispatcher-jobs-in-usa')->value('content');

    expect($content)->toContain('one year in a high-volume telephone, customer service or high stress environment')
        ->toContain('Texas Commission on Law Enforcement (TCOLE) telecommunicator training program')
        ->toContain('2025 posting, filing closed')
        ->toContain('Public Response Dispatcher I</strong> jobs at <strong>$54,648&ndash;$73,644</strong>')
        ->toContain('Fire Dispatcher II is a promotion open only to County Fire employees')
        ->toContain('<strong>94.14% of 911 calls in under 15 seconds</strong>, against a national standard of 90%')
        ->toContain('<strong>$51,790 minimum/year</strong>')
        ->toContain('30 college semester credits')
        ->toContain('<strong>18-month probation</strong>')
        ->toContain('the June 2026 academy paid <strong>$29.00 an hour</strong>')
        ->toContain('<strong>median annual wage of $53,040</strong>')
        ->not->toContain('$64,308')
        ->not->toContain('average answer time')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'police-officer-jobs-in-usa' => Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        'federal-police-jobs-in-usa' => Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        'customer-service-jobs-in-usa' => Database\Seeders\CustomerServiceJobsUsaBlogSeeder::class,
        'government-security-jobs-in-australia' => Database\Seeders\GovernmentSecurityJobsAustraliaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/emergency-dispatcher-jobs-in-usa');
    }
});

it('fixes the US law enforcement draft age, education, training and qualities claims with official sources', function () {
    $this->seed(Database\Seeders\LawEnforcementJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', 'law-enforcement-jobs-in-usa')->value('content');

    expect($content)->toContain('appointed at <strong>20 years and 6 months</strong>')
        ->toContain('<strong>24 college semester credits</strong> with a 2.0 index')
        ->toContain('<strong>$60,884 to start</strong>')
        ->toContain('<strong>$126,410</strong> in total salary after 5 1/2 years')
        ->toContain('<strong>state and local laws, constitutional law, civil rights and police ethics</strong>')
        ->toContain("<strong>Perceptiveness</strong>, to anticipate people's reactions")
        ->toContain("Bachelor's degree and two years of full-time professional work, or an advanced degree and one year")
        ->toContain('the current USAJOBS posting says you must not have reached your 37th birthday on appointment')
        ->toContain('fish and game wardens <strong>-6%</strong>')
        ->toContain('<strong>median annual wage of $77,310</strong>')
        ->toContain('the DEA lists US citizenship as a condition of all DEA employment')
        ->not->toContain('minimum appointment age of 21')
        ->not->toContain('report writing, emergency response')
        ->not->toContain('utm_source=chatgpt.com');

    expect(Blog::where('slug', 'law-enforcement-jobs-in-usa')->value('meta_title'))->toBe('Law Enforcement Jobs in USA: Requirements and Pay');

    $this->seed(Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class);

    expect(Blog::where('slug', 'police-officer-jobs-in-usa')->value('content'))
        ->toContain('the NYPD starts officers on $60,884')
        ->not->toContain('$55,942');

    $siblings = [
        'police-officer-jobs-in-usa' => Database\Seeders\PoliceOfficerJobsUsaBlogSeeder::class,
        'federal-police-jobs-in-usa' => Database\Seeders\FederalPoliceJobsUsaBlogSeeder::class,
        'emergency-dispatcher-jobs-in-usa' => Database\Seeders\EmergencyDispatcherJobsUsaBlogSeeder::class,
        'intelligence-analyst-jobs-in-usa' => Database\Seeders\IntelligenceAnalystJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/law-enforcement-jobs-in-usa');
    }
});

it('fixes the Canada public safety draft careers link, CSC experience, CBSA and RCMP rule claims with official sources', function () {
    $this->seed(Database\Seeders\PublicSafetyJobsCanadaBlogSeeder::class);

    $content = Blog::where('slug', 'public-safety-jobs-in-canada')->value('content');

    expect($content)->toContain('https://www.publicsafety.gc.ca/cnt/bt/crrs/index-en.aspx')
        ->toContain('Work experience is not an essential qualification')
        ->toContain('<strong>Standard First Aid with CPR Level C and AED</strong>')
        ->toContain('at least <strong>18</strong> before starting the Officer Induction Training Program. There is <strong>no maximum age</strong>')
        ->toContain('CBSA will not consider a combination of education, training and experience instead')
        ->toContain('<strong>$80,344 to $89,462</strong> a year')
        ->toContain('<strong>$86,915 to $103,079</strong>')
        ->toContain('<strong>tax-free allowance of $525 a week</strong>')
        ->toContain('<strong>15 weeks in residence</strong>')
        ->toContain('<strong>Enhanced Reliability Status plus Secret clearance</strong>')
        ->toContain('permanent residents need <strong>1,095 days in Canada</strong>')
        ->toContain('<strong>19 to be hired</strong>')
        ->toContain('Cadets receive <strong>$1,000 a week</strong> at Depot, up to $26,000')
        ->toContain('<strong>moderate risk of shortage</strong>')
        ->not->toContain('https://www.canada.ca/en/public-safety-canada/corporate/careers.html')
        ->not->toContain('direct-interaction experience')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'how-to-become-a-correctional-officer-in-canada' => Database\Seeders\CorrectionalOfficerJobsCanadaBlogSeeder::class,
        'government-security-jobs-in-australia' => Database\Seeders\GovernmentSecurityJobsAustraliaBlogSeeder::class,
        'emergency-dispatcher-jobs-in-usa' => Database\Seeders\EmergencyDispatcherJobsUsaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'law-enforcement-jobs-in-usa' => Database\Seeders\LawEnforcementJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/public-safety-jobs-in-canada');
    }
});

it('fixes the US account manager draft salary, openings and job title claims with official sources', function () {
    $this->seed(Database\Seeders\AccountManagerJobsUsaBlogSeeder::class);

    $content = Blog::where('slug', 'account-manager-jobs-in-usa')->value('content');

    expect($content)->toContain('<strong>$164,350 is the mean, not the median.</strong>')
        ->toContain('<strong>$148,270</strong> a year ($71.28 an hour)')
        ->toContain('under $73,170')
        ->toContain('<strong>$69,990</strong>')
        ->toContain('the 90th percentile <strong>$148,840</strong>')
        ->toContain('about <strong>47,300 openings a year</strong>')
        ->toContain('about <strong>123,400 openings a year</strong>')
        ->toContain('<strong>no "Account Manager"</strong>')
        ->toContain('education "varies by product type"')
        ->toContain('$229,000 to $369,600')
        ->not->toContain('29,000 openings')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'customer-service-jobs-in-usa' => Database\Seeders\CustomerServiceJobsUsaBlogSeeder::class,
        'digital-marketing-jobs-in-usa' => Database\Seeders\DigitalMarketingJobsUsaBlogSeeder::class,
        'sales-jobs-in-australia' => Database\Seeders\SalesJobsAustraliaBlogSeeder::class,
        'remote-customer-service-jobs' => Database\Seeders\RemoteCustomerServiceJobsBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/account-manager-jobs-in-usa');
    }
});

it('fixes the Canada finance analyst draft designation, skills and employer link claims with official sources', function () {
    $this->seed(Database\Seeders\FinanceAnalystJobsCanadaBlogSeeder::class);

    $content = Blog::where('slug', 'finance-analyst-jobs-in-canada')->value('content');

    expect($content)->toContain('or another recognised designation such as CFP or CIM, is <strong>usually required</strong>')
        ->toContain('Those come from job postings, not from Job Bank')
        ->toContain('median wage of $43.27 an hour')
        ->toContain('$72.36')
        ->toContain('Saskatchewan has the highest provincial median at $49.00')
        ->toContain('updated these ratings on <strong>10 December 2025</strong>')
        ->toContain('over the period of 2024-2033 at the national level')
        ->toContain('scotiabank.com/careers/en/careers.html')
        ->toContain('jobs.bmo.com')
        ->toContain('usually work more than 40 hours a week')
        ->not->toContain('scotiabank.com/ca/en/about/careers.html')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'accountant-jobs-in-uae' => Database\Seeders\AccountantJobsUaeBlogSeeder::class,
        'visa-sponsorship-jobs-in-canada' => Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder::class,
        'jobs-in-canada-for-foreign-workers' => Database\Seeders\JobsInCanadaForeignWorkersBlogSeeder::class,
        'account-manager-jobs-in-usa' => Database\Seeders\AccountManagerJobsUsaBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/finance-analyst-jobs-in-canada');
    }
});

it('fixes the UK business analyst draft visa rate, entry route and employer claims with official sources', function () {
    $this->seed(Database\Seeders\BusinessAnalystJobsUkBlogSeeder::class);

    $content = Blog::where('slug', 'business-analyst-jobs-in-uk')->value('content');

    expect($content)->toContain('<strong>&pound;52,970 median</strong>')
        ->toContain('90th &pound;93,273')
        ->toContain('<strong>&pound;50,200</strong> a year (&pound;25.74 an hour)')
        ->toContain('only for Health and Care Worker applicants or people whose first certificate of sponsorship predates 4 April 2024')
        ->toContain('70% of the standard going rate, which is <strong>&pound;35,100</strong>')
        ->toContain('absolute floor of <strong>&pound;33,400</strong>')
        ->toContain('at least <strong>&pound;41,700</strong> a year')
        ->toContain('lists exactly <strong>three routes</strong>')
        ->toContain('six levels for the profession')
        ->toContain('last updated <strong>18 September 2026</strong>')
        ->toContain('JIRA and Confluence')
        ->not->toContain('Azure DevOps')
        ->not->toContain('Forestry Commission')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'jobs-in-uk-for-foreigners' => Database\Seeders\JobsInUkForForeignersBlogSeeder::class,
        'it-support-jobs-in-uk' => Database\Seeders\ItSupportJobsUkBlogSeeder::class,
        'marketing-jobs-in-uk' => Database\Seeders\MarketingJobsUkBlogSeeder::class,
        'office-assistant-jobs-in-uk' => Database\Seeders\OfficeAssistantJobsUkBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/business-analyst-jobs-in-uk');
    }
});

it('fixes the US long-haul truck driver draft pay, openings and training timeline claims with official sources', function () {
    $this->seed(Database\Seeders\LongHaulTruckDriverUsaBlogSeeder::class);

    $content = Blog::where('slug', 'how-to-become-a-long-haul-truck-driver-in-usa')->value('content');

    expect($content)->toContain('more than <strong>$79,380</strong>')
        ->toContain('less than $40,140')
        ->toContain('about <strong>214,500 openings a year</strong>')
        ->toContain('<strong>no federal source publishes cents-per-mile rates or average annual mileage</strong>')
        ->toContain('not eligible to take the CDL skills test in the first 14 days')
        ->toContain('no federal minimum number of behind-the-wheel hours')
        ->toContain('score of at least <strong>80%</strong>')
        ->toContain('<strong>concluded on 7 November 2025</strong>')
        ->toContain('<strong>23 June 2025</strong>')
        ->toContain('<strong>18 November 2024</strong>')
        ->toContain('<strong>$85.25</strong>')
        ->not->toContain('$78,800')
        ->not->toContain('237,600')
        ->not->toContain('utm_source=chatgpt.com');

    $siblings = [
        'cdl-driver-jobs-in-usa' => Database\Seeders\CdlDriverJobsUsaBlogSeeder::class,
        'delivery-driver-jobs-in-usa' => Database\Seeders\DeliveryDriverJobsUsaBlogSeeder::class,
        'bus-driver-jobs-in-canada' => Database\Seeders\BusDriverJobsCanadaBlogSeeder::class,
        'heavy-truck-driver-jobs-in-saudi-arabia' => Database\Seeders\HeavyTruckDriverJobsSaudiBlogSeeder::class,
    ];

    foreach ($siblings as $slug => $seeder) {
        $this->seed($seeder);

        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/how-to-become-a-long-haul-truck-driver-in-usa');
    }
});

it('resolves every internal link the new guides publish', function () {
    // A /blog/ link to a slug no seeder produces is a 404 the sitemap will
    // happily advertise, and it is the easiest mistake to make when a guide
    // is written before the guide it points at.
    $seeders = [];

    foreach (glob(database_path('seeders').'/*BlogSeeder.php') as $file) {
        $class = 'Database\\Seeders\\'.basename($file, '.php');

        if (class_exists($class)) {
            $seeders[] = $class;
        }
    }

    // The truck driver guide predates the *BlogSeeder naming convention.
    $seeders[] = Database\Seeders\FirstBlogPostSeeder::class;

    foreach ($seeders as $seeder) {
        $this->seed($seeder);
    }

    $known = Blog::pluck('slug')->all();

    $guides = [
        'delivery-driver-jobs-in-uk',
        'customer-service-jobs-in-canada',
        'construction-worker-jobs-in-australia',
        'data-entry-jobs-in-usa',
        'data-entry-jobs-in-pakistan',
        'security-guard-jobs-in-uae',
        'office-assistant-jobs-in-australia',
        'retail-jobs-in-usa',
        'factory-worker-jobs-in-germany',
        'farm-worker-jobs-in-canada',
        'data-scientist-jobs-in-usa',
        'electrician-jobs-in-uk',
        'plumber-jobs-in-australia',
        'receptionist-jobs-in-uae',
        'delivery-driver-jobs-in-usa',
        'teacher-jobs-in-pakistan',
        'nurse-jobs-in-saudi-arabia',
        'it-support-jobs-in-uk',
        'cashier-jobs-in-usa',
        'cook-jobs-in-uk',
        'accountant-jobs-in-uae',
        'police-officer-jobs-in-usa',
        'sales-jobs-in-australia',
        'healthcare-assistant-jobs-in-uk',
        'registered-nurse-jobs-in-usa',
        'help-desk-technician-jobs-in-usa',
        'housekeeper-jobs-in-uk',
        'teacher-jobs-in-usa',
        'administrative-assistant-jobs-in-usa',
        'devops-engineer-jobs-in-germany',
        'federal-police-jobs-in-usa',
        'welder-jobs-in-canada',
        'bus-driver-jobs-in-canada',
        'remote-jobs-in-usa',
        'visa-sponsorship-jobs-in-canada',
        'no-experience-jobs-in-australia',
        'kitchen-helper-jobs-in-saudi-arabia',
        'retail-associate-jobs-in-usa',
        'database-administrator-jobs-in-usa',
        'medical-assistant-jobs-in-usa',
        'teaching-jobs-in-uk',
        'carpenter-jobs-in-usa',
        'taxi-driver-jobs-in-australia',
        'work-from-home-jobs-in-uk',
        'visa-sponsorship-jobs-in-australia',
        'no-experience-jobs-in-saudi-arabia',
        'store-assistant-jobs-in-uk',
        'qa-tester-jobs-in-canada',
        'cdl-driver-jobs-in-usa',
        'online-jobs-in-pakistan',
        'mechanic-jobs-in-saudi-arabia',
        'janitor-jobs-in-canada',
        'system-administrator-jobs-in-uae',
        'medical-receptionist-jobs-in-australia',
        'receptionist-jobs-in-australia',
        'dental-assistant-jobs-in-canada',
        'school-nurse-jobs-in-usa',
        'security-specialist-jobs-in-uae',
        'recruiter-jobs-in-canada',
        'heavy-equipment-operator-jobs-in-canada',
        'forklift-operator-jobs-in-usa',
        'work-from-home-jobs-in-usa',
        'jobs-in-uk-for-foreigners',
        'entry-level-it-jobs',
        'customer-service-jobs-in-usa',
        'healthcare-support-jobs-in-uk',
        'online-teacher-jobs-worldwide',
        'intelligence-analyst-jobs-in-usa',
        'marketing-jobs-in-uk',
        'maintenance-technician-jobs-in-usa',
        'heavy-truck-driver-jobs-in-saudi-arabia',
        'online-data-entry-jobs',
        'jobs-in-canada-for-foreign-workers',
        'call-center-jobs-in-pakistan',
        'office-assistant-jobs-in-uk',
        'personal-care-assistant-jobs-in-australia',
        'physical-therapist-jobs-in-usa',
        'occupational-therapist-jobs-in-canada',
        'how-to-get-an-esl-teaching-job-in-japan',
        'how-to-become-a-correctional-officer-in-canada',
        'how-to-become-a-sales-representative-in-australia',
        'how-to-get-a-logistics-driver-job-in-the-uae',
        'how-to-get-a-remote-customer-service-job-with-no-experience',
        'how-to-become-an-auto-mechanic-in-australia',
        'entry-level-healthcare-jobs',
        'preschool-teacher-jobs-in-canada',
        'how-foreign-workers-can-get-a-job-in-australia',
        'tutor-jobs-in-usa',
        'education-assistant-jobs-in-australia',
        'school-administrator-jobs-in-uk',
        'educational-support-jobs-in-usa',
        'government-security-jobs-in-australia',
        'emergency-dispatcher-jobs-in-usa',
        'law-enforcement-jobs-in-usa',
        'public-safety-jobs-in-canada',
        'account-manager-jobs-in-usa',
        'finance-analyst-jobs-in-canada',
        'business-analyst-jobs-in-uk',
        'how-to-become-a-long-haul-truck-driver-in-usa',
    ];

    foreach ($guides as $guide) {
        preg_match_all('#/blog/([a-z0-9-]+)#', Blog::where('slug', $guide)->value('content'), $matches);

        foreach (array_unique($matches[1]) as $referenced) {
            expect($known)->toContain($referenced);
        }
    }
});
