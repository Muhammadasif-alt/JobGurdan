<?php

use App\Models\Blog;
use Database\Seeders\ApplyForTradeApprenticeshipBlogSeeder;
use Database\Seeders\ApprenticeshipRankingListsBlogSeeder;
use Database\Seeders\ApprenticeshipTestMathBasicsBlogSeeder;
use Database\Seeders\AvoidLosingMoneyFakeJobAgentsBlogSeeder;
use Database\Seeders\BestCountriesTruckDriversAbroadBlogSeeder;
use Database\Seeders\BestJobSitesWorkAbroadBlogSeeder;
use Database\Seeders\BestPayingJobsAbroadWithoutDegreeBlogSeeder;
use Database\Seeders\BestTradesSkillsGulfCountriesBlogSeeder;
use Database\Seeders\BlueCollarJobsAbroadOver3000BlogSeeder;
use Database\Seeders\BoilermakerApprenticeshipRequirementsBlogSeeder;
use Database\Seeders\BringFamilyWorkingAbroadBlogSeeder;
use Database\Seeders\CaregiverJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\CleanerJobUkFromPakistanBlogSeeder;
use Database\Seeders\CountriesHiringForeignWorkersBlogSeeder;
use Database\Seeders\DocumentsNeededToWorkAbroadBlogSeeder;
use Database\Seeders\ElevatorApprenticeshipChicagoPhiladelphiaBostonBlogSeeder;
use Database\Seeders\ElevatorApprenticeshipTestNeiepBlogSeeder;
use Database\Seeders\EmployerCancelsWorkVisaBlogSeeder;
use Database\Seeders\EnglishLanguageRequirementsWorkingAbroadBlogSeeder;
use Database\Seeders\FakeOverseasJobOfferBlogSeeder;
use Database\Seeders\FindJobAbroadWithoutRecruiterBlogSeeder;
use Database\Seeders\FreeOnlineCoursesJobAbroadBlogSeeder;
use Database\Seeders\GulfVsEuropeForWorkBlogSeeder;
use Database\Seeders\HgvDriverJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\HgvDriverJobUkFromPakistanBlogSeeder;
use Database\Seeders\HgvDriverSalaryUkBlogSeeder;
use Database\Seeders\HighestPayingBlueCollarJobsAbroadBlogSeeder;
use Database\Seeders\HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\HowLongWorkVisaTakesBlogSeeder;
use Database\Seeders\IronworkerApprenticeshipNewYorkBlogSeeder;
use Database\Seeders\IronworkerAptitudeTestBlogSeeder;
use Database\Seeders\JobAbroadWithoutExperienceBlogSeeder;
use Database\Seeders\JobEuropeNonEuCitizenBlogSeeder;
use Database\Seeders\JobsAbroadFreeHousingFoodBlogSeeder;
use Database\Seeders\JobsHiringNowHoustonTxBlogSeeder;
use Database\Seeders\JobsInChicagoIlBlogSeeder;
use Database\Seeders\LaborersUnionEntranceExamBlogSeeder;
use Database\Seeders\MechanicalReasoningQuestionsBlogSeeder;
use Database\Seeders\MistakesApplyingJobsAbroadBlogSeeder;
use Database\Seeders\MoveAbroadForWorkStepByStepBlogSeeder;
use Database\Seeders\OneWeekBeforeApprenticeshipTestBlogSeeder;
use Database\Seeders\OnlineJobsUsaFromPakistanBlogSeeder;
use Database\Seeders\OperatingEngineersApprenticeshipTestBlogSeeder;
use Database\Seeders\PartTimeJobsSeattleWaBlogSeeder;
use Database\Seeders\PlumberApprenticeshipSeattleHoustonDetroitBlogSeeder;
use Database\Seeders\PrepareOnlineJobInterviewAbroadBlogSeeder;
use Database\Seeders\ResumeForJobAbroadBlogSeeder;
use Database\Seeders\RetakeApprenticeshipAptitudeTestBlogSeeder;
use Database\Seeders\RightsForeignWorkersAbroadBlogSeeder;
use Database\Seeders\SaveMoneyWorkingAbroadBlogSeeder;
use Database\Seeders\SendMoneyHomeWorkingAbroadBlogSeeder;
use Database\Seeders\SheetMetalApprenticeshipClevelandPittsburghMinneapolisBlogSeeder;
use Database\Seeders\SheetMetalWorkersAptitudeTestBlogSeeder;
use Database\Seeders\SkilledTradesLongIslandHudsonValleyBlogSeeder;
use Database\Seeders\TopSkillsJobAbroadFastBlogSeeder;
use Database\Seeders\TradesCareersUpstateNewYorkBlogSeeder;
use Database\Seeders\TruckDriverSalaryUsaBlogSeeder;
use Database\Seeders\UaPlumberPipefitterApprenticeshipTestBlogSeeder;
use Database\Seeders\UnionApprenticeshipsSouthernCaliforniaInlandEmpireBlogSeeder;
use Database\Seeders\UnionApprenticeshipWaitlistsBlogSeeder;
use Database\Seeders\VerifyOverseasJobOfferBlogSeeder;
use Database\Seeders\VisaSponsorshipCountriesSkilledWorkersBlogSeeder;
use Database\Seeders\WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\WarehouseOperativeSalaryUkBlogSeeder;
use Database\Seeders\WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\WhatIsVisaSponsorshipBlogSeeder;
use Database\Seeders\WhatIsWorkPermitBlogSeeder;
use Database\Seeders\WhatScoreNeededApprenticeshipTestBlogSeeder;
use Database\Seeders\WorkAbroadVsStayHomeBlogSeeder;
use Database\Seeders\WorkVisaVsWorkPermitBlogSeeder;
use Database\Seeders\WorkVisaWithoutAgentBlogSeeder;

dataset('abroadGuides', [
    'countries hiring' => [CountriesHiringForeignWorkersBlogSeeder::class, ['Canada', 'Skills in Demand', '£41,700', '22 July 2025', 'Opportunity Card', 'Pakistan is not on the UK Youth Mobility list']],
    'blue collar' => [HighestPayingBlueCollarJobsAbroadBlogSeeder::class, ['$109,910', '$46,960', 'May 2025', 'PKR 30,774,800', 'Red Seal']],
    'no experience' => [JobAbroadWithoutExperienceBlogSeeder::class, ['jetprogramme.org', 'Pakistan is not on the current list', '6 points', 'A1', 'Pakistan is not on the list of eligible countries']],
    'fake offer' => [FakeOverseasJobOfferBlogSeeder::class, ['reportfraud.police.uk', 'Police Scotland on 101', 'Overseas Employment Promoters', 'beoe.gov.pk', 'Register of Licensed Sponsors']],
    'skilled worker countries' => [VisaSponsorshipCountriesSkilledWorkersBlogSeeder::class, ['€50,700', '€45,934.20', 'Skills in Demand', 'Certificate of Sponsorship', '22 July 2025', 'COMPASS']],
    'no agent' => [WorkVisaWithoutAgentBlogSeeder::class, ['Step 8', 'authorised immigration representative', 'beoe.gov.pk', 'Official Fees']],
    'over 3000' => [BlueCollarJobsAbroadOver3000BlogSeeder::class, ['$31.02', '$5,377', 'PKR 1,505,560', '$17.31', 'May 2025']],
    'documents' => [DocumentsNeededToWorkAbroadBlogSeeder::class, ['DS-160', 'certified translations', 'Ministry of Foreign Affairs', 'Work Abroad Checklist']],
    'job sites' => [BestJobSitesWorkAbroadBlogSeeder::class, ['SeasonalJobs', 'NHS Jobs', 'Overseas Employment Promoters', 'Official (Canada)']],
    'resume' => [ResumeForJobAbroadBlogSeeder::class, ['Europass', 'reverse-chronological', 'YourName_Resume.pdf', '/resume-writing-services']],
    'find without recruiter' => [FindJobAbroadWithoutRecruiterBlogSeeder::class, ['Step 8', 'Workforce Australia', 'SeasonalJobs', 'Overseas Employment Promoters', 'Subject:']],
    'work abroad vs stay home' => [WorkAbroadVsStayHomeBlogSeeder::class, ['$4,000', 'ten months', 'Accredited Employer Work Visa', 'Overseas Employment Promoters']],
    'save money abroad' => [SaveMoneyWorkingAbroadBlogSeeder::class, ['AED 2,700', '$8,400', '$6,400', 'how-much-it-costs', 'Overseas Employment Promoters']],
    'english requirements' => [EnglishLanguageRequirementsWorkingAbroadBlogSeeder::class, ['level B2', '8 January 2026', 'Ecctis', 'skill level 3 to 5']],
    'send money home' => [SendMoneyHomeWorkingAbroadBlogSeeder::class, ['PKR 138,600', 'PKR 137,500', 'consumerfinance.gov', 'hundi']],
    'work permit' => [WhatIsWorkPermitBlogSeeder::class, ['open work permits', 'Employment Pass', 'Accredited Employer Work Visa']],
    'visa sponsorship' => [WhatIsVisaSponsorshipBlogSeeder::class, ['Certificate of Sponsorship', 'cannot charge you fees', 'register of licensed sponsors']],
    'visa timing' => [HowLongWorkVisaTakesBlogSeeder::class, ['within 3 weeks', '80% within 5 weeks', '10 business days']],
    'bring family' => [BringFamilyWorkingAbroadBlogSeeder::class, ['11 March 2024', 'Partner of a Worker Work Visa', 'Employment Pass or S Pass']],
    'employer cancels visa' => [EmployerCancelsWorkVisaBlogSeeder::class, ['60 days', 'up to 90 days', 'Short-Term Visit Pass']],
    'application mistakes' => [MistakesApplyingJobsAbroadBlogSeeder::class, ['National Careers Service', 'foreign candidates search', 'SeasonalJobs']],
    'verify offer' => [VerifyOverseasJobOfferBlogSeeder::class, ['Companies House', 'register of licensed sponsors', 'public register of recognised sponsors']],
    'free courses' => [FreeOnlineCoursesJobAbroadBlogSeeder::class, ['freeCodeCamp', 'IBM SkillsBuild', 'HubSpot Academy', 'OpenLearn']],
    'online interview' => [PrepareOnlineJobInterviewAbroadBlogSeeder::class, ['Pakistan time', 'reportfraud.ftc.gov', 'National Careers Service']],
    'visa vs permit' => [WorkVisaVsWorkPermitBlogSeeder::class, ['Employment Authorization Document', 'EU Blue Card', 'not a visa']],
    'gulf trades' => [BestTradesSkillsGulfCountriesBlogSeeder::class, ['MoHRE', 'HRSD', 'no single Gulf trade visa']],
    'fake job agents' => [AvoidLosingMoneyFakeJobAgentsBlogSeeder::class, ['beoe.gov.pk', 'agency-workers-your-rights/fees', 'complaint.nccia.gov.pk']],
    'worker rights' => [RightsForeignWorkersAbroadBlogSeeder::class, ['Fair Work Ombudsman', 'Health and Safety Executive', 'worker employment status']],
    'europe non eu' => [JobEuropeNonEuCitizenBlogSeeder::class, ['Opportunity Card', 'Critical Skills Employment Permit', 'EURES']],
    'gulf vs europe' => [GulfVsEuropeForWorkBlogSeeder::class, ['does not levy personal income tax', 'Golden visa', 'Scotland']],
    'move abroad' => [MoveAbroadForWorkStepByStepBlogSeeder::class, ['Step 10', 'emigrant protection', 'beoe.gov.pk']],
    'free housing food' => [JobsAbroadFreeHousingFoodBlogSeeder::class, ['Fact Sheet 26', 'three meals a day', 'cooking facilities']],
    'best paying no degree' => [BestPayingJobsAbroadWithoutDegreeBlogSeeder::class, ['$109,910', '$63,190', 'May 2025', '$58,640']],
    'ironworker test' => [IronworkerAptitudeTestBlogSeeder::class, ['minimum of 70% on both tests', 'Grade 10', '60-minute online assessment']],
    'elevator test' => [ElevatorApprenticeshipTestNeiepBlogSeeder::class, ['100 multiple-choice questions', '70% correct', '15 to 20 minutes', 'tool assessment']],
    'sheet metal test' => [SheetMetalWorkersAptitudeTestBlogSeeder::class, ['Local 20', 'NexGEN Accuplacer', 'fifteen-minute timed math test', '35 of 45']],
    'ua plumber test' => [UaPlumberPipefitterApprenticeshipTestBlogSeeder::class, ['Bennett Mechanical Aptitude', 'cumulative score of 17', 'Local 467']],
    'boilermaker test' => [BoilermakerApprenticeshipRequirementsBlogSeeder::class, ['Written Test: No', 'Local 555', '576 classroom hours', 'Group 4']],
    'laborers exam' => [LaborersUnionEntranceExamBlogSeeder::class, ['4,000 hours of on-the-job training', '400 hours of classroom training', '60%, 70%, 80%']],
    'operating engineers test' => [OperatingEngineersApprenticeshipTestBlogSeeder::class, ['Local 49', 'Local 150', 'six-month wait', '6,000']],
    'apprenticeship math' => [ApprenticeshipTestMathBasicsBlogSeeder::class, ['0.375', '1 5/12', '28.3 square feet', '30 feet per hour']],
    'mechanical reasoning' => [MechanicalReasoningQuestionsBlogSeeder::class, ['10 teeth', 'fulcrum', 'Bennett Mechanical Aptitude']],
    'apprenticeship retake' => [RetakeApprenticeshipAptitudeTestBlogSeeder::class, ['seven days', 'six-month wait', 'cannot be retaken']],
    'apprenticeship score' => [WhatScoreNeededApprenticeshipTestBlogSeeder::class, ['cumulative WorkKeys score of 17', '35 of 45', 'minimum of 70%']],
    'apprenticeship week plan' => [OneWeekBeforeApprenticeshipTestBlogSeeder::class, ['Day 6', '35 reading, 35 mechanical and 30 maths', 'Apprenticeship.gov']],
    'elevator cities' => [ElevatorApprenticeshipChicagoPhiladelphiaBostonBlogSeeder::class, ['600 applications', '400 applications', 'America/New_York', 'five-year education programme']],
    'ironworker new york' => [IronworkerApprenticeshipNewYorkBlogSeeder::class, ['March 2027', '35-foot column climb', 'USD 100 apprenticeship initiation fee', 'six months before recruitment']],
    'plumber cities' => [PlumberApprenticeshipSeattleHoustonDetroitBlogSeeder::class, ['valid for only 15 days', 'December 18, 2026', 'raw score of 48', '8,500 to 10,000 hours']],
    'sheet metal cities' => [SheetMetalApprenticeshipClevelandPittsburghMinneapolisBlogSeeder::class, ['verification email', '20 March 2027', '8,000 hours', 'dependent on employment with a contractor']],
    'upstate trades' => [TradesCareersUpstateNewYorkBlogSeeder::class, ['S. 3546 California Road', '70% or higher', 'Bennett Mechanical Comprehension Test', 'third Wednesday']],
    'long island hudson' => [SkilledTradesLongIslandHudsonValleyBlogSeeder::class, ['1600 Walt Whitman Road', '270 Motor Parkway', 'only 25 applications', 'Bergen County']],
    'southern california' => [UnionApprenticeshipsSouthernCaliforniaInlandEmpireBlogSeeder::class, ['76%', 'last Tuesday of each month', '4,800 hours', 'currently closed for Apprenticeship Program applications']],
    'chicago jobs' => [JobsInChicagoIlBlogSeeder::class, ['upcoming opportunities page', 'does not hire them directly', 'about 90 days']],
    'houston jobs' => [JobsHiringNowHoustonTxBlogSeeder::class, ['Job Interest Card', 'pass drug testing', 'NEOGOV']],
    'seattle part time' => [PartTimeJobsSeattleWaBlogSeeder::class, ['at least 10 hours a week', 'work-study eligible', 'typically for about two weeks']],
    'apply for trade apprenticeship' => [ApplyForTradeApprenticeshipBlogSeeder::class, ['33 items, 46 minutes', '36 items, 51 minutes', 'official transcript', 'one full year of high school algebra']],
    'apprenticeship ranking lists' => [ApprenticeshipRankingListsBlogSeeder::class, ['Pool of Eligibles (not yet apprentices)', 'average of the two interviewers', '70% is the minimum', 'two years, or until the recruitment list is exhausted', '1,000 hours']],
    'union apprenticeship waitlists' => [UnionApprenticeshipWaitlistsBlogSeeder::class, ['March, June, October and December', 'will result in your application being deleted', 'signatory contractor', 'six months before reapplying']],
    'top skills' => [TopSkillsJobAbroadFastBlogSeeder::class, ['professions-in-demand', 'skill-occupation-list', 'Pakistan Nursing Council', 'English language requirement']],
    'online jobs usa' => [OnlineJobsUsaFromPakistanBlogSeeder::class, ['Form W-8BEN', 'USD 5,000', 'EPD Circular Letter No. 06 of 2026', '$92,650', 'reportfraud.ftc.gov']],
    'truck drivers' => [BestCountriesTruckDriversAbroadBlogSeeder::class, ['Code 95', '$58,640', 'Temporary Shortage List', 'FMCSA', 'Pakistani licence is not accepted']],
]);

const OFFICIAL_HOSTS = [
    'www.jobbank.gc.ca', 'www.canada.ca', 'immi.homeaffairs.gov.au', 'www.immigration.govt.nz', 'www.gov.uk',
    'www.make-it-in-germany.com', 'ind.nl', 'enterprise.gov.ie', 'u.ae', 'www.mom.gov.sg', 'www.moj.go.jp',
    'www.udi.no', 'www.sem.admin.ch', 'jetprogramme.org', 'eures.europa.eu', 'reportfraud.ftc.gov',
    'reportfraud.police.uk', 'antifraudcentre-centreantifraude.ca', 'www.scamwatch.gov.au', 'beoe.gov.pk',
    'www.consumerfinance.gov', 'www.sbp.org.pk', 'find-and-update.company-information.service.gov.uk', 'www.freecodecamp.org', 'learn.microsoft.com', 'www.skillsbuild.org', 'academy.hubspot.com', 'www.open.edu', 'www.fairwork.gov.au', 'www.dol.gov', 'www.hse.gov.uk', 'www.mohre.gov.ae', 'complaint.nccia.gov.pk', 'www.hrsd.gov.sa', 'www.uscis.gov', 'home-affairs.ec.europa.eu', 'european-union.europa.eu', 'eures.europa.eu', 'www.france-visas.gouv.fr', 'employment-social-affairs.ec.europa.eu', 'www.bls.gov', 'www.nmc.org.uk',
    'iw721.org', 'www.dli.mn.gov', 'www.ironworkers.org', 'www.neiep.org', 'www.seattlepipetrades.org', 'ualocal32.com', 'www.plu68.com', 'www.plumbers98tc.org', 'lni.wa.gov', 'tsbpe.texas.gov', 'www.michigan.gov', 'cle33sheetmetal.org', 'sheetmetaltraining.org', 'sheetmetalapprenticetraining.com', 'www.smw10.org', 'dli.mn.gov', 'dol.ny.gov', 'www.ieetc.org', 'www.laett.com', 'www.ualocal364.com', 'www.wsctf.org', 'www.cnoett.org', 'www.ua373.com', 'neieprecruitment.org', 'iueclocal4.com', 'nycironworkers.org', 'www.ironworkers580.org', 'www.ironworkers12.org', 'chicago.taleo.net', 'www.transitchicago.com', 'www.cps.edu', 'ides.illinois.gov', 'illinoisjoblink.illinois.gov', 'www.houstontx.gov', 'www.governmentjobs.com', 'jobs.ridemetro.org', 'hrrm.harriscountytx.gov', 'www.workintexas.com', 'www.seattle.gov', 'www.washington.edu', 'hfs.uw.edu', 'www.worksourcewa.com', 'www.electricaltrainingalliance.org', 'www.etasv.org', 'nietc.org', 'www.oregon.gov', 'www.laborerstrainingschool.com', 'sm88jatc.org', 'smw20training.com', 'www.smart18ecwi.org', 'www.socalsma.org', 'www.sheetmetal-iti.org', 'ua.org', 'www.pftf597.org', 'www.ua190.org', 'www.ualocal467.org', 'sajac-boilermakers.org', 'boilermaker.ca', '555.boilermaker.ca', 'www.dir.ca.gov', 'boilermakers.org', 'www.bnap.com', 'www.liuna.org', 'www.liunatraining.org', 'www.njlaborers.org', 'liunaminnesota.org', 'www.iuoe.org', 'local150.org', 'www.local49training.org', 'www.apprenticeship.gov', 'www.osha.gov', 'openstax.org',
    'www.fmcsa.dot.gov', 'www.irs.gov', 'reportfraud.ftc.gov', 'jobsearch.gov.au', 'www.jobs.nhs.uk', 'seasonaljobs.dol.gov', 'travel.state.gov', 'europass.europa.eu', 'nationalcareers.service.gov.uk',
];

it('seeds a published guide within the SEO limits', function (string $seeder) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $seeder::SLUG)->firstOrFail();
    $tags = array_map('trim', explode(',', $blog->tags));

    expect($blog->status)->toBe('published')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($tags)->toHaveCount(8)
        ->and(mb_check_encoding($blog->tags.$blog->meta_title.$blog->meta_description, 'ASCII'))->toBeTrue()
        ->and($blog->featured_image)->toStartWith('blogs/');
})->with('abroadGuides');

it('has eight FAQs and eight People Also Search For entries', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
    [$before, $pasf] = explode('<h2>People Also Search For</h2>', $content);
    [, $faq] = explode('<h2>Frequently Asked Questions</h2>', $before);
    [$pasfBlock] = explode('<h2>More Job Guides</h2>', $pasf);

    expect(substr_count($faq, '<h3>'))->toBe(8)
        ->and(substr_count($pasfBlock, '<h3>'))->toBe(8);
})->with('abroadGuides');

it('links only to JobGader pages and official portals', function (string $seeder) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
    preg_match_all('/href="([^"]+)"/', $content, $matches);

    foreach ($matches[1] as $href) {
        if (str_starts_with($href, '/')) {
            continue;
        }

        expect(OFFICIAL_HOSTS)->toContain(parse_url($href, PHP_URL_HOST));
    }

    expect(strtolower($content))->not->toContain('indeed.com')
        ->not->toContain('apply now')
        ->not->toContain('add real source');

    if ($seeder !== BestJobSitesWorkAbroadBlogSeeder::class) {
        expect(strtolower($content))->not->toContain('indeed');
    }
})->with('abroadGuides');

it('states the checked facts', function (string $seeder, array $facts) {
    $this->seed($seeder);

    $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;

    foreach ($facts as $fact) {
        expect($content)->toContain($fact);
    }
})->with('abroadGuides');

it('renders the guide page and survives a re-seed', function (string $seeder) {
    $this->seed($seeder);
    $this->seed($seeder);

    expect(Blog::where('slug', $seeder::SLUG)->count())->toBe(1);

    $this->get('/blog/'.$seeder::SLUG)->assertOk();
})->with('abroadGuides');

it('cross-links only to guides that exist once they are all seeded', function () {
    $new = [
        CountriesHiringForeignWorkersBlogSeeder::class,
        HighestPayingBlueCollarJobsAbroadBlogSeeder::class,
        JobAbroadWithoutExperienceBlogSeeder::class,
        FakeOverseasJobOfferBlogSeeder::class,
        BestCountriesTruckDriversAbroadBlogSeeder::class,
        VisaSponsorshipCountriesSkilledWorkersBlogSeeder::class,
        WorkVisaWithoutAgentBlogSeeder::class,
        BlueCollarJobsAbroadOver3000BlogSeeder::class,
        DocumentsNeededToWorkAbroadBlogSeeder::class,
        BestJobSitesWorkAbroadBlogSeeder::class,
        ResumeForJobAbroadBlogSeeder::class,
        FindJobAbroadWithoutRecruiterBlogSeeder::class,
        WorkAbroadVsStayHomeBlogSeeder::class,
        SaveMoneyWorkingAbroadBlogSeeder::class,
        TopSkillsJobAbroadFastBlogSeeder::class,
        IronworkerAptitudeTestBlogSeeder::class,
        ElevatorApprenticeshipTestNeiepBlogSeeder::class,
        SheetMetalWorkersAptitudeTestBlogSeeder::class,
        UaPlumberPipefitterApprenticeshipTestBlogSeeder::class,
        BoilermakerApprenticeshipRequirementsBlogSeeder::class,
        LaborersUnionEntranceExamBlogSeeder::class,
        OperatingEngineersApprenticeshipTestBlogSeeder::class,
        ApprenticeshipTestMathBasicsBlogSeeder::class,
        MechanicalReasoningQuestionsBlogSeeder::class,
        RetakeApprenticeshipAptitudeTestBlogSeeder::class,
        WhatScoreNeededApprenticeshipTestBlogSeeder::class,
        OneWeekBeforeApprenticeshipTestBlogSeeder::class,
        ApplyForTradeApprenticeshipBlogSeeder::class,
        ElevatorApprenticeshipChicagoPhiladelphiaBostonBlogSeeder::class,
        IronworkerApprenticeshipNewYorkBlogSeeder::class,
        PlumberApprenticeshipSeattleHoustonDetroitBlogSeeder::class,
        SheetMetalApprenticeshipClevelandPittsburghMinneapolisBlogSeeder::class,
        TradesCareersUpstateNewYorkBlogSeeder::class,
        SkilledTradesLongIslandHudsonValleyBlogSeeder::class,
        UnionApprenticeshipsSouthernCaliforniaInlandEmpireBlogSeeder::class,
        JobsInChicagoIlBlogSeeder::class,
        JobsHiringNowHoustonTxBlogSeeder::class,
        PartTimeJobsSeattleWaBlogSeeder::class,
        ApprenticeshipRankingListsBlogSeeder::class,
        UnionApprenticeshipWaitlistsBlogSeeder::class,
        EnglishLanguageRequirementsWorkingAbroadBlogSeeder::class,
        SendMoneyHomeWorkingAbroadBlogSeeder::class,
        WhatIsWorkPermitBlogSeeder::class,
        WhatIsVisaSponsorshipBlogSeeder::class,
        HowLongWorkVisaTakesBlogSeeder::class,
        BringFamilyWorkingAbroadBlogSeeder::class,
        EmployerCancelsWorkVisaBlogSeeder::class,
        MistakesApplyingJobsAbroadBlogSeeder::class,
        VerifyOverseasJobOfferBlogSeeder::class,
        FreeOnlineCoursesJobAbroadBlogSeeder::class,
        PrepareOnlineJobInterviewAbroadBlogSeeder::class,
        WorkVisaVsWorkPermitBlogSeeder::class,
        BestTradesSkillsGulfCountriesBlogSeeder::class,
        AvoidLosingMoneyFakeJobAgentsBlogSeeder::class,
        RightsForeignWorkersAbroadBlogSeeder::class,
        JobEuropeNonEuCitizenBlogSeeder::class,
        GulfVsEuropeForWorkBlogSeeder::class,
        MoveAbroadForWorkStepByStepBlogSeeder::class,
        JobsAbroadFreeHousingFoodBlogSeeder::class,
        BestPayingJobsAbroadWithoutDegreeBlogSeeder::class,
        OnlineJobsUsaFromPakistanBlogSeeder::class,
    ];

    foreach ([
        ...$new,
        TruckDriverSalaryUsaBlogSeeder::class,
        CaregiverJobsUsaVisaSponsorshipBlogSeeder::class,
        HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::class,
        WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder::class,
        WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder::class,
        WarehouseOperativeSalaryUkBlogSeeder::class,
        HgvDriverJobsUkVisaSponsorshipBlogSeeder::class,
        HgvDriverSalaryUkBlogSeeder::class,
        HgvDriverJobUkFromPakistanBlogSeeder::class,
        CleanerJobUkFromPakistanBlogSeeder::class,
    ] as $seeder) {
        $this->seed($seeder);
    }

    $slugs = Blog::pluck('slug')->all();

    foreach ($new as $seeder) {
        $content = Blog::where('slug', $seeder::SLUG)->firstOrFail()->content;
        preg_match_all('#href="/blog/([^"]+)"#', $content, $matches);

        foreach ($matches[1] as $slug) {
            if (in_array($slug, ['carpenter-jobs-in-usa', 'virtual-assistant-jobs-in-pakistan', 'remote-frontend-developer-jobs-in-usa'], true)) {
                continue;
            }

            expect($slugs)->toContain($slug);
        }
    }
});
