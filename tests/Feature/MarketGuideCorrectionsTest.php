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
    ];

    foreach ($guides as $guide) {
        preg_match_all('#/blog/([a-z0-9-]+)#', Blog::where('slug', $guide)->value('content'), $matches);

        foreach (array_unique($matches[1]) as $referenced) {
            expect($known)->toContain($referenced);
        }
    }
});
