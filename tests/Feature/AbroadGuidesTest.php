<?php

use App\Models\Blog;
use Database\Seeders\AffiliateMarketingBeginnersKarachiSindhBlogSeeder;
use Database\Seeders\ApplyForTradeApprenticeshipBlogSeeder;
use Database\Seeders\ApprenticeshipRankingListsBlogSeeder;
use Database\Seeders\ApprenticeshipTestMathBasicsBlogSeeder;
use Database\Seeders\AvoidLosingMoneyFakeJobAgentsBlogSeeder;
use Database\Seeders\BackOfficeExecutiveJobsDelhiDutiesSalaryBlogSeeder;
use Database\Seeders\BestCountriesTruckDriversAbroadBlogSeeder;
use Database\Seeders\BestJobSitesWorkAbroadBlogSeeder;
use Database\Seeders\BestPayingJobsAbroadWithoutDegreeBlogSeeder;
use Database\Seeders\BestTradesSkillsGulfCountriesBlogSeeder;
use Database\Seeders\BlueCollarJobsAbroadOver3000BlogSeeder;
use Database\Seeders\BoilermakerApprenticeshipRequirementsBlogSeeder;
use Database\Seeders\BpoJobsPuneWhatToExpectDayOneBlogSeeder;
use Database\Seeders\BringFamilyWorkingAbroadBlogSeeder;
use Database\Seeders\BugBountyHuntingKarachiSindhBeginnersBlogSeeder;
use Database\Seeders\CallCentreJobsKarachiSindhFreshersBlogSeeder;
use Database\Seeders\CaregiverJobsCalgaryVisaSponsorshipBlogSeeder;
use Database\Seeders\CaregiverJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\ChefJobsParisFranceEntryPathsBlogSeeder;
use Database\Seeders\CleanerJobUkFromPakistanBlogSeeder;
use Database\Seeders\CnaJobsAtlantaGeorgiaCertificationPayBlogSeeder;
use Database\Seeders\ContentWriterJobsLahoreHowToGetHiredBlogSeeder;
use Database\Seeders\CopywriterJobsBirminghamEnglandBeginnersBlogSeeder;
use Database\Seeders\CountriesHiringForeignWorkersBlogSeeder;
use Database\Seeders\CustomsOfficerJobsVancouverHowToApplyBlogSeeder;
use Database\Seeders\DataAnalystJobsDublinIrelandBlogSeeder;
use Database\Seeders\DataEntryJobsHomePennsylvaniaLegitRedFlagsBlogSeeder;
use Database\Seeders\DataEntryJobsHoustonNoExperienceBlogSeeder;
use Database\Seeders\DeliveryDriverJobsLosAngelesAppsRequirementsBlogSeeder;
use Database\Seeders\DocumentsNeededToWorkAbroadBlogSeeder;
use Database\Seeders\ElevatorApprenticeshipChicagoPhiladelphiaBostonBlogSeeder;
use Database\Seeders\ElevatorApprenticeshipTestNeiepBlogSeeder;
use Database\Seeders\EmailMarketingJobsVancouverBritishColumbiaBlogSeeder;
use Database\Seeders\EmployerCancelsWorkVisaBlogSeeder;
use Database\Seeders\EnglishLanguageRequirementsWorkingAbroadBlogSeeder;
use Database\Seeders\EntryLevelSeoJobsNewYorkCityBlogSeeder;
use Database\Seeders\FakeOverseasJobOfferBlogSeeder;
use Database\Seeders\FindJobAbroadWithoutRecruiterBlogSeeder;
use Database\Seeders\FirefighterJobsChicagoApplicationPhysicalTestBlogSeeder;
use Database\Seeders\FreelanceWritingJobsNairobiKenyaBlogSeeder;
use Database\Seeders\FreeOnlineCoursesJobAbroadBlogSeeder;
use Database\Seeders\FrontEndDeveloperJobsManchesterEnglandBlogSeeder;
use Database\Seeders\GasStationJobsHoustonHiringAgeRequirementsBlogSeeder;
use Database\Seeders\GulfVsEuropeForWorkBlogSeeder;
use Database\Seeders\HgvDriverJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\HgvDriverJobUkFromPakistanBlogSeeder;
use Database\Seeders\HgvDriverSalaryUkBlogSeeder;
use Database\Seeders\HighestPayingBlueCollarJobsAbroadBlogSeeder;
use Database\Seeders\HomeHealthAideJobsBrooklynNewYorkTrainingHiringBlogSeeder;
use Database\Seeders\HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\HotelHousekeepingJobsLasVegasNoExperienceBlogSeeder;
use Database\Seeders\HotelJobsDubaiForeignersRolesRequirementsBlogSeeder;
use Database\Seeders\HotelReceptionistJobsOrlandoFloridaBlogSeeder;
use Database\Seeders\HowLongWorkVisaTakesBlogSeeder;
use Database\Seeders\HowToFindJobAbroadFromPakistan2026BlogSeeder;
use Database\Seeders\IronworkerApprenticeshipNewYorkBlogSeeder;
use Database\Seeders\IronworkerAptitudeTestBlogSeeder;
use Database\Seeders\JanitorJobsPhiladelphiaNoExperienceBlogSeeder;
use Database\Seeders\JobAbroadWithoutExperienceBlogSeeder;
use Database\Seeders\JobEuropeNonEuCitizenBlogSeeder;
use Database\Seeders\JobsAbroadFreeHousingFoodBlogSeeder;
use Database\Seeders\JobsHiringNowHoustonTxBlogSeeder;
use Database\Seeders\JobsInChicagoIlBlogSeeder;
use Database\Seeders\JuniorWebDeveloperJobsChicagoNoDegreeBlogSeeder;
use Database\Seeders\LaborersUnionEntranceExamBlogSeeder;
use Database\Seeders\LabTechnicianJobsLahorePunjabQualificationsSalaryBlogSeeder;
use Database\Seeders\LivingWorkingAbroad2026MinimumWageCostOfLivingBlogSeeder;
use Database\Seeders\MechanicalReasoningQuestionsBlogSeeder;
use Database\Seeders\MedicalAssistantJobsPhoenixNoExperienceBlogSeeder;
use Database\Seeders\MistakesApplyingJobsAbroadBlogSeeder;
use Database\Seeders\MoveAbroadForWorkStepByStepBlogSeeder;
use Database\Seeders\NurseJobsLondonOverseasRegistrationBlogSeeder;
use Database\Seeders\NursingJobsHoustonLicensingEntryRequirementsBlogSeeder;
use Database\Seeders\NursingJobsRiyadhRequirementsContractBasicsBlogSeeder;
use Database\Seeders\NursingJobsTorontoForeignTrainedNursesBlogSeeder;
use Database\Seeders\OfficeAssistantJobsGlasgowEntryGuideBlogSeeder;
use Database\Seeders\OneWeekBeforeApprenticeshipTestBlogSeeder;
use Database\Seeders\OnlineJobsUsaFromPakistanBlogSeeder;
use Database\Seeders\OnlineTutoringJobsNewJerseyBlogSeeder;
use Database\Seeders\OperatingEngineersApprenticeshipTestBlogSeeder;
use Database\Seeders\ParamedicJobsTorontoTrainingCertificationBlogSeeder;
use Database\Seeders\PartTimeJobsSeattleWaBlogSeeder;
use Database\Seeders\PetrolPumpAttendantJobsLahoreDutiesSalaryBlogSeeder;
use Database\Seeders\PharmacyTechnicianJobsColumbusOhioRequirementsBlogSeeder;
use Database\Seeders\PlumberApprenticeshipSeattleHoustonDetroitBlogSeeder;
use Database\Seeders\PoliceConstableJobsPunjabPakistanBlogSeeder;
use Database\Seeders\PoliceConstableRecruitmentUttarPradeshBlogSeeder;
use Database\Seeders\PpcSpecialistJobsAtlantaSkillsCertificationsBlogSeeder;
use Database\Seeders\PrepareOnlineJobInterviewAbroadBlogSeeder;
use Database\Seeders\PrisonOfficerJobsLondonRequirementsSelectionBlogSeeder;
use Database\Seeders\ReceptionistJobsDubaiRequirementsInterviewTipsBlogSeeder;
use Database\Seeders\RemoteCustomerSupportJobsFloridaBlogSeeder;
use Database\Seeders\RemoteJobsBeginnersIslamabadPakistanBlogSeeder;
use Database\Seeders\RemoteSeoJobsDenverColoradoBlogSeeder;
use Database\Seeders\RemoteWordPressDeveloperJobsTorontoOntarioBlogSeeder;
use Database\Seeders\ResumeForJobAbroadBlogSeeder;
use Database\Seeders\RetakeApprenticeshipAptitudeTestBlogSeeder;
use Database\Seeders\RightsForeignWorkersAbroadBlogSeeder;
use Database\Seeders\SaveMoneyWorkingAbroadBlogSeeder;
use Database\Seeders\SecurityGuardJobsDubaiNoExperienceBlogSeeder;
use Database\Seeders\SecurityGuardJobsHoustonLicenseTrainingBlogSeeder;
use Database\Seeders\SendMoneyHomeWorkingAbroadBlogSeeder;
use Database\Seeders\SeoExecutiveJobsMumbaiSalarySkillsBlogSeeder;
use Database\Seeders\SheetMetalApprenticeshipClevelandPittsburghMinneapolisBlogSeeder;
use Database\Seeders\SheetMetalWorkersAptitudeTestBlogSeeder;
use Database\Seeders\SkilledTradesLongIslandHudsonValleyBlogSeeder;
use Database\Seeders\SocialMediaManagerJobsFromHomeMelbourneBlogSeeder;
use Database\Seeders\SoftwareDeveloperJobsSeattleWashingtonBlogSeeder;
use Database\Seeders\SweeperJobsKarachiGovernmentPrivateOptionsBlogSeeder;
use Database\Seeders\TopSkillsJobAbroadFastBlogSeeder;
use Database\Seeders\TradesCareersUpstateNewYorkBlogSeeder;
use Database\Seeders\TranscriptionJobsFromHomeOhioBlogSeeder;
use Database\Seeders\TruckDriverJobsDallasCdlRequirementsTrainingBlogSeeder;
use Database\Seeders\TruckDriverSalaryUsaBlogSeeder;
use Database\Seeders\UaPlumberPipefitterApprenticeshipTestBlogSeeder;
use Database\Seeders\UnionApprenticeshipsSouthernCaliforniaInlandEmpireBlogSeeder;
use Database\Seeders\UnionApprenticeshipWaitlistsBlogSeeder;
use Database\Seeders\VerifyOverseasJobOfferBlogSeeder;
use Database\Seeders\VirtualAssistantJobsManilaPhilippinesBlogSeeder;
use Database\Seeders\VisaSponsorshipCountriesSkilledWorkersBlogSeeder;
use Database\Seeders\WaiterJobsDohaQatarRequirementsInterviewTipsBlogSeeder;
use Database\Seeders\WarehouseOperativeJobsUkVisaSponsorshipBlogSeeder;
use Database\Seeders\WarehouseOperativeSalaryUkBlogSeeder;
use Database\Seeders\WarehouseWorkerJobsUsaVisaSponsorshipBlogSeeder;
use Database\Seeders\WhatIsVisaSponsorshipBlogSeeder;
use Database\Seeders\WhatIsWorkPermitBlogSeeder;
use Database\Seeders\WhatScoreNeededApprenticeshipTestBlogSeeder;
use Database\Seeders\WordPressDeveloperJobsAustinTexasBlogSeeder;
use Database\Seeders\WorkAbroadVsStayHomeBlogSeeder;
use Database\Seeders\WorkFromHomeJobsMichiganNoExperienceBlogSeeder;
use Database\Seeders\WorkVisasPakistanis2026CountryGuideBlogSeeder;
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
    'office assistant jobs glasgow entry guide' => [OfficeAssistantJobsGlasgowEntryGuideBlogSeeder::class, ['There are no fixed entry routes to becoming an administrator', '£21,000', '£28,000', '£26,031.26', '£12.71 an hour', 'open to current University of Glasgow employees only']],
    'find job abroad from pakistan 2026' => [HowToFindJobAbroadFromPakistan2026BlogSeeder::class, ['strictly prohibits working while holding a visit or tourist visa', 'Mexico or participating Caribbean countries', '£340', 'at no cost', '€45,630', '33,182']],
    'living working abroad 2026' => [LivingWorkingAbroad2026MinimumWageCostOfLivingBlogSeeder::class, ['£12.71', '£1,406', '€13.90', '$17.95', '$26.44', '$7.25', 'no minimum salary stipulated in the UAE Labour Law']],
    'work visas pakistanis 2026' => [WorkVisasPakistanis2026CountryGuideBlogSeeder::class, ['£819', '£1,618', '£1,035', '£340', 'C$155', 'C$100', '€75', 'AED 250 to AED 3,450', '€55,770']],
    'police constable jobs punjab pakistan' => [PoliceConstableJobsPunjabPakistanBlogSeeder::class, ['Website Under Maintenance', '718', '31 October 2026', '5 feet 7 inches', 'Rs. 300', '3,298']],
    'police constable recruitment uttar pradesh' => [PoliceConstableRecruitmentUttarPradeshBlogSeeder::class, ['32,679', '30 January 2026', '168 cm', '4.8 km run within 25 minutes', '2.4 km run within 14 minutes', 'one-time relaxation of three years']],
    'security guard jobs houston' => [SecurityGuardJobsHoustonLicenseTrainingBlogSeeder::class, ['minimum of 45 hours', 'within 90 days of the application date', '6-hour self-paced online course costing $60', 'candidates from outside the security industry are welcome', 'IdentoGO']],
    'security guard jobs dubai no experience' => [SecurityGuardJobsDubaiNoExperienceBlogSeeder::class, ['age of 21 to 60', 'G4S provides training for many of our roles', 'strictly prohibits working while holding a visit or tourist visa', 'bleep test']],
    'firefighter jobs chicago' => [FirefighterJobsChicagoApplicationPhysicalTestBlogSeeder::class, ['No person may be appointed after age 37', '$74,626', '10 minutes and 20 seconds', '8801-CFD-2022-FF-EMT', 'at least two timed practice runs']],
    'paramedic jobs toronto' => [ParamedicJobsTorontoTrainingCertificationBlogSeeder::class, ['open until 18 November 2026', '895 Eastern Ave', '210 days', 'CPR Level C is not accepted', 'four-semester Ontario College Diploma']],
    'prison officer jobs london' => [PrisonOfficerJobsLondonRequirementsSelectionBlogSeeder::class, ['£43,453', '£41,290 to £43,290', '29 April 2026', 'level 5.4', 'within 34 seconds', '7-week foundation programme']],
    'customs officer jobs vancouver' => [CustomsOfficerJobsVancouverHowToApplyBlogSeeder::class, ['24 June 2027', '$80,344 to $89,462', '$525 a week', 'not a pass or fail exercise', '2026-EA-OIM-FB-02-0010']],
    'nursing jobs houston' => [NursingJobsHoustonLicensingEntryRequirementsBlogSeeder::class, ['65,910', '$99,830', '$63,670', 'You have 60 days from the time you move', 'Houston Methodist Baytown only']],
    'nurse jobs london overseas' => [NurseJobsLondonOverseasRegistrationBlogSeeder::class, ['1 October 2026', '£1,226', '£167', '£794', 'but not the visa', '£324']],
    'nursing jobs riyadh' => [NursingJobsRiyadhRequirementsContractBasicsBlogSeeder::class, ['must not be below one year for non-Saudis', '15 to 25 working days', 'IELTS 5.5', 'Article 40', '180 days']],
    'nursing jobs toronto foreign trained' => [NursingJobsTorontoForeignTrainedNursesBlogSeeder::class, ['$433', '$454', '7 to 14 weeks', 'writing 6.5, speaking 7, listening 7, reading 6.5', '400 hours', 'at least 140 hours']],
    'cna jobs atlanta' => [CnaJobsAtlantaGeorgiaCertificationPayBlogSeeder::class, ['minimum of 85 hours', '70 multiple-choice questions', '23,260', '$18.92', '$39,350', 'there is no renewal fee']],
    'caregiver jobs calgary' => [CaregiverJobsCalgaryVisaSponsorshipBlogSeeder::class, ['will not accept new applications under the Home Care Worker Immigration Pilots', '30 March 2030', '19 November 2025', '$20.27', '15 to 27 weeks', '52.9%']],
    'medical assistant jobs phoenix' => [MedicalAssistantJobsPhoenixNoExperienceBlogSeeder::class, ['class 3 misdemeanor', '2,000 hours', '15,410', '$22.51', '$46,830', '$45,690']],
    'truck driver jobs dallas' => [TruckDriverJobsDallasCdlRequirementsTrainingBlogSeeder::class, ['26,001 pounds', '10,000 pounds', 'at least 14 days', '7 February 2022', '1 June 2026', '$58,640 a year', 'H-2A']],
    'petrol pump attendant jobs lahore' => [PetrolPumpAttendantJobsLahoreDutiesSalaryBlogSeeder::class, ['Rs 40,000 a month', 'Rs 1,538.46 a day', 'Rs 192.31 an hour', 'Rs 100 a meal', 'Rs 1,800 a month', 'Rs 2,000 a month', '8 September 2025', 'exclusive licensee of the Shell brand']],
    'sweeper jobs karachi' => [SweeperJobsKarachiGovernmentPrivateOptionsBlogSeeder::class, ['Rs 43,000', 'Rs 44,484', 'Rs 53,350', 'Rs 55,626', '1 July 2026', 'outsourcing its municipal functions', '25 October 2025']],
    'janitor jobs philadelphia' => [JanitorJobsPhiladelphiaNoExperienceBlogSeeder::class, ['$13.50 an hour', '1563843', '$34,910 a year', 'six months of paid, full-time custodial housekeeping work', 'four days of paid training', '$7.25 an hour', 'one year\'s residence']],
    'delivery driver jobs los angeles' => [DeliveryDriverJobsLosAngelesAppsRequirementsBlogSeeder::class, ['21 in California', '$18.42 an hour from 1 July 2026', '$16.90 from 1 January 2026', '$0.37 a mile in 2026', '120% of the applicable minimum wage']],
    'gas station jobs houston' => [GasStationJobsHoustonHiringAgeRequirementsBlogSeeder::class, ['16 or older', 'no minimum employee age', '4085 Gulf Freeway', '$7.25 an hour', 'signed employee notification form']],
    'chef jobs paris' => [ChefJobsParisFranceEntryPathsBlogSeeder::class, ['EUR 12.31 gross an hour', 'EUR 1,867.02 gross a month', 'EUR 1,477.93 net', '39 hours', '75% refund of the transport pass', 'at least a year of restaurant experience']],
    'waiter jobs doha' => [WaiterJobsDohaQatarRequirementsInterviewTipsBlogSeeder::class, ['QAR 1,000 a month', 'QAR 300 for food', 'QAR 500 for housing', 'Law No. 19 of 2020', '30 August 2020', 'two months if longer']],
    'hotel housekeeping jobs las vegas' => [HotelHousekeepingJobsLasVegasNoExperienceBlogSeeder::class, ['60 hours over 2 weeks, $1,200', '100 hours over 4 weeks, $1,600', '$23.14 an hour', '$17.34', '$24.22', '$12.00 an hour', '21 or older']],
    'hotel jobs dubai foreigners' => [HotelJobsDubaiForeignersRolesRequirementsBlogSeeder::class, ['13 types of work permit', 'AED 250 to AED 3,450', 'Article 6 of Federal Decree-Law No. 33 of 2021', '26 Dubai listings', 'sets no minimum salary']],
    'hotel receptionist jobs orlando' => [HotelReceptionistJobsOrlandoFloridaBlogSeeder::class, ['26135347', '$17.43 an hour', '$15.13', '$20.81', '$15.00 an hour', '30 September 2026', '$3,120', '36 matches']],
    'pharmacy technician jobs columbus' => [PharmacyTechnicianJobsColumbusOhioRequirementsBlogSeeder::class, ['18 months', '24 months', '$45', '3,480', '$21.75', '140-hour externship', '$129']],
    'home health aide jobs brooklyn' => [HomeHealthAideJobsBrooklynNewYorkTrainingHiringBlogSeeder::class, ['at least 75 hours', '59 hours in the classroom and 16 hours', '$19.65', '$19.86 to $22.00', '626 Sheepshead Bay Road']],
    'lab technician jobs lahore' => [LabTechnicianJobsLahorePunjabQualificationsSalaryBlogSeeder::class, ['not less than 45%', 'two-year', 'BSc in MLT and one year of experience', '5 March 2022', 'no gender preference']],
    'back office executive jobs delhi duties salary' => [BackOfficeExecutiveJobsDelhiDutiesSalaryBlogSeeder::class, ['does not charge any fee at any stage of registration, job application or interview processing', '1 to 4 years', 'NISM certification preferred', 'Compensation will be aligned with market standards', '3 to 6 years', 'Rs 2,40,000']],
    'receptionist jobs dubai requirements interview tips' => [ReceptionistJobsDubaiRequirementsInterviewTipsBlogSeeder::class, ['Article 6 of Federal Decree-Law No. 33 of 2021', 'does not apply to workers employed on a shift basis', 'Ministerial Resolution No. 340 of 2026', 'Proficiency in Opera', 'Queen Elizabeth 2', 'already closed when we checked']],
    'call centre jobs karachi sindh freshers' => [CallCentreJobsKarachiSindhFreshersBlogSeeder::class, ['O Levels/Matric or above', 'American or neutral accent', 'valid CNIC', '6 months of proven experience in a customer-facing role', 'no minimum educational requirement', 'Batch 05 of DigiSkills 3.0 opens on 2 November 2026']],
    'bpo jobs pune what to expect day one' => [BpoJobsPuneWhatToExpectDayOneBlogSeeder::class, ['does not charge any fee at any stage of registration, job application or interview processing', 'business process outsourcing', 'Accenture', 'Concentrix', 'There is no universal training duration', 'cost to company']],
    'data entry jobs houston no experience' => [DataEntryJobsHoustonNoExperienceBlogSeeder::class, ['$19.00 an hour', '$39,520 a year', '4,430 data entry keyers', 'decline 25.5%', 'posted on 22 October 2026', 'Honest employers, including the federal government, will never ask you to pay to get a job']],
    'data entry jobs home pennsylvania legit red flags' => [DataEntryJobsHomePennsylvaniaLegitRedFlagsBlogSeeder::class, ['free job-matching system', '$18.66 an hour', '$38,810 a year', 'usually $5 to $20', 'No honest potential employer will ever send you a check and then tell you to send on part of the money', 'Signal Messenger']],
    'social media manager jobs from home melbourne' => [SocialMediaManagerJobsFromHomeMelbourneBlogSeeder::class, ['contractors run their own business and sell their services to others', 'the employer controls how, where and when they do their work', 'having an ABN is not the deciding factor of whether someone is a contractor', 'a place Australians can search and apply for jobs', 'contractors have different responsibilities from employees around insurance, tax and super']],
    'seo executive jobs mumbai salary skills' => [SeoExecutiveJobsMumbaiSalarySkillsBlogSeeder::class, ['helping search engines understand your content', 'no secrets that will automatically rank your site first in Google', 'does not charge any fee at any stage of registration, job application or interview processing', 'Rs 3,00,000', 'URL Inspection Tool']],
    'content writer jobs lahore how to get hired' => [ContentWriterJobsLahoreHowToGetHiredBlogSeeder::class, ['Batch 05 would start from 2 November 2026', 'the Ministry of IT and Telecom conceived the programme', 'people-first content is created primarily for people', 'it is critical to manually fact check and review AI-generated content', 'EPD Circular Letter No. 06 of 2026']],
    'transcription jobs from home ohio' => [TranscriptionJobsFromHomeOhioBlogSeeder::class, ['$40,410 per year ($19.43 per hour)', 'decline 4 percent from 2025 to 2035', 'net earnings from self-employment were $400 or more', 'Social Security and Medicare tax primarily for individuals who work for themselves', 'will ever send you a check to deposit', 'a way to find a job center near you']],
    'work from home jobs michigan no experience' => [WorkFromHomeJobsMichiganNoExperienceBlogSeeder::class, ['1-800-285-9675', 'more than 90 centers across the state', '$44,770 per year ($21.53 per hour)', 'decline 5% from 2025 to 2035', '$48,310 per year ($23.23 per hour)', 'net earnings from self-employment were $400 or more']],
    'remote jobs beginners islamabad pakistan' => [RemoteJobsBeginnersIslamabadPakistanBlogSeeder::class, ['no minimum educational requirement', 'Batch 05 of DigiSkills 3.0 opens on 2 November 2026 at 11:00 AM', 'strictly on a first-come, first-served basis', 'up to two courses in one batch', 'USD 5,000 or 50% of proceeds a month, whichever is higher', 'e-certificate issued by the Virtual University of Pakistan']],
    'copywriter jobs birmingham england beginners' => [CopywriterJobsBirminghamEnglandBeginnersBlogSeeder::class, ['£24,000 a year at the starter level and £45,000 a year at the experienced level', '37 to 40 a week', 'Junior Advertising Creative Level 3 Advanced Apprenticeship', '£12.71 an hour for workers aged 21 and over and £8.00 an hour for apprentices', 'effective 1 April 2026', 'if you are 16 or over']],
    'email marketing jobs vancouver british columbia' => [EmailMarketingJobsVancouverBritishColumbiaBlogSeeder::class, ['hourly wages of $21.00 at the low end, a median of $33.65 and $56.59 at the high end', 'The reference period is 2023-2024', 'within 10 business days or less', 'up to 2 years, or 6 months for inquiries or applications', 'valid for at least 60 days after sending', 'showed 87 results when we checked it on 22 October 2026']],
    'affiliate marketing beginners karachi sindh' => [AffiliateMarketingBeginnersKarachiSindhBlogSeeder::class, ['I get commissions for purchases made through links in this post.', 'enrolment for Batch-05 opens on 2 November 2026', 'conceived by the Ministry of IT and Telecom', 'EPD Circular Letter No. 06 of 2026', 'US law applies when it is reasonably foreseeable that the post will affect US consumers', 'Affiliate marketing is not a job']],
    'entry level seo jobs new york city' => [EntryLevelSeoJobsNewYorkCityBlogSeeder::class, ['offers free services at our network of Workforce1 Career Centers', "there are no secrets here that'll automatically rank your site first in Google", 'how Google crawls, indexes and serves websites', 'honest employers, including the federal government, will never ask you to pay to get a job', 'career workshops and one-on-one career counselling']],
    'remote seo jobs denver colorado' => [RemoteSeoJobsDenverColoradoBlogSeeder::class, ["there are no secrets here that'll automatically rank your site first in Google", 'net earnings from self-employment are $400 or more', 'identifying key performance indicators', 'honest employers, including the federal government, will never ask you to pay to get a job']],
    'ppc specialist jobs atlanta skills certifications' => [PpcSpecialistJobsAtlantaSkillsCertificationsBlogSeeder::class, ['You need a score of 80% or greater on the certification assessment', 'You have 75 minutes to take an assessment', 'you can retake it one day later', 'which Google states is one year', 'earning all three gives Microsoft Advertising Certified Expert status']],
    'remote support florida' => [RemoteCustomerSupportJobsFloridaBlogSeeder::class, ['16 jobs', 'Employ Florida', 'bilingual', 'hardwired']],
    'bug bounty karachi' => [BugBountyHuntingKarachiSindhBeginnersBlogSeeder::class, ['Veriff', '37 currencies', 'third-party account', 'two-factor authentication', 'entirely at their discretion']],
    'virtual assistant manila' => [VirtualAssistantJobsManilaPhilippinesBlogSeeder::class, ['PhilJobNet', 'Makati', '25 Mbps', 'graveyard shift']],
    'online tutoring nj' => [OnlineTutoringJobsNewJerseyBlogSeeder::class, ['5 Mbps', '75%', '33%', 'One-Stop Career Centers', 'H-1B']],
    'freelance writing nairobi' => [FreelanceWritingJobsNairobiKenyaBlogSeeder::class, ['70,000 Kenyan shillings', '80% of the client-approved payment', '0% to 15%', 'three days']],
    'software seattle' => [SoftwareDeveloperJobsSeattleWashingtonBlogSeeder::class, ['$167,280', '$132,800', '$211,860', '$13,940']],
    'frontend manchester' => [FrontEndDeveloperJobsManchesterEnglandBlogSeeder::class, ['£27,000', '£60,000', 'not accepting applications for 2026', 'Find an apprenticeship']],
    'data analyst dublin' => [DataAnalystJobsDublinIrelandBlogSeeder::class, ['valid employment permit', 'SOC 2010']],
    'wordpress austin' => [WordPressDeveloperJobsAustinTexasBlogSeeder::class, ['$99,000', '$47.59', '$8,250', '13 August 2026', 'Plugin Developer Handbook']],
    'wordpress toronto' => [RemoteWordPressDeveloperJobsTorontoOntarioBlogSeeder::class, ['globally distributed, remote team', 'Fueled', '@automattic.com', 'Richmond Hill']],
    'junior web chicago' => [JuniorWebDeveloperJobsChicagoNoDegreeBlogSeeder::class, ['$99,520', '5 percent', 'Chicago Apprentice Network', 'Aon and Zurich North America']],
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
    'iw721.org', 'www.dli.mn.gov', 'www.ironworkers.org', 'www.neiep.org', 'tpr.fmcsa.dot.gov', 'clr.org.pk', 'www.radio.gov.pk', 'www.psopk.com', 'wafi-energy.com', 'careers.totalparco.com.pk', 'lhr.sindh.gov.pk', 'sswmb.gos.pk', 'www.aku.edu', 'careers.compass-usa.com', 'aa080.referrals.selectminds.com', 'www.phila.gov', 'www.uber.com', 'dasher.doordash.com', 'hiring.amazon.com', 'wagesla.lacity.gov', 'www.treasurer.ca.gov', 'www.caljobs.ca.gov', 'careers.quiktrip.com', 'careers.7-eleven.com', 'efte.twc.texas.gov', 'www.tabc.texas.gov', 'comptroller.texas.gov', 'www.service-public.fr', 'candidat.francetravail.fr', 'www.ilo.org', 'jobs.smartrecruiters.com', 'www.theculinaryacademy.org', 'labor.nv.gov', 'careers.mgmresorts.com', 'wynnresorts.com', 'www.employnv.gov', 'radissoncareers.com', 'www.jumeirah.com', 'floridajobs.org', 'jobs.hilton.com', 'careers.loewshotels.com', 'www.pharmacy.ohio.gov', 'www.cscc.edu', 'www.ptcb.org', 'www.health.ny.gov', 'selfhelp.net', 'jobs.vnshealth.org', 'pmfpunjab.edu.pk', 'chughtailab.com', 'www.ahpc.org.pk', 'shaukatkhanum.org.pk', 'www.azleg.gov', 'www.caahep.org', 'www.bannerhealth.com', 'www.azjobconnection.gov', 'alis.alberta.ca', 'www.cno.org', 'cno.org', 'sunnybrook.ca', 'www.uhn.ca', 'nurseaide.allianthealth.org', 'kb-ga.credentia.com', 'www.emoryhealthcare.org', 'piedmontcareers.org', 'www.bon.texas.gov', 'bon.texas.gov', 'nursecompact.com', 'jobs.memorialhermann.org', 'www.houstonmethodist.org', 'www.houstonmethodistcareers.org', 'jobs.harrishealth.org', 'www.imperial.nhs.uk', 'scfhs.org.sa', 'dataflowgroup.com', 'www.kfshrc.edu.sa', 'prisonandprobationjobs.gov.uk', 'www.prison-officer-online-ac.co.uk', 'www.cbsa-asfc.gc.ca', 'emploisfp-psjobs.cfp-psc.gc.ca', 'www.chicago.gov', 'www.iaff.org', 'www.toronto.ca', 'humber.ca', 'forms.mgcs.gov.on.ca', 'www.sira.gov.ae', 'careers.g4s.com', 'www.dps.texas.gov', 'teex.org', 'jobs.aus.com', 'punjabpolice.gov.pk', 'jobs.punjab.gov.pk', 'uppbpb.gov.in', 'www.ons.gov.uk', 'www.bmas.de', 'www.ontario.ca', 'ircc.canada.ca', 'pakistan.diplo.de', 'digital.diplo.de', 'findajob.dwp.gov.uk', 'www.myworldofwork.co.uk', 'www.gla.ac.uk', 'jobs.gla.ac.uk', 'myjobscotland.gov.uk', 'www.apexsinfo.com', 'www.pocketful.in', 'careers.ibex.co', 'careers.accor.com', 'careers.marriott.com', 'www.njp.gov.pk', 'data.bls.gov', 'www.pa.gov', 'www.pacareerlink.pa.gov', 'www.identitytheft.gov', 'hayselectricalservices.applytojob.com', 'www.hayselectrical.com', 'business.gov.au', 'ohiomeansjobs.ohio.gov', 'www.digiskills.pk', 'www.michiganworks.org', 'digiskills.pk', 'fightspam-combattrelepourriel.ised-isde.canada.ca', 'www.ftc.gov', 'consumer.ftc.gov', 'developers.google.com', 'support.google.com', 'www.about.ads.microsoft.com', 'www.nyc.gov', 'www.employflorida.com', 'www.hackerone.com', 'hackerone.com', 'docs.hackerone.com', 'www.bugcrowd.com', 'docs.bugcrowd.com', 'portswigger.net', 'philjobnet.gov.ph', 'www.tutor.com', 'support.wyzant.com', 'preply.com', 'www.nj.gov', 'support.upwork.com', 'help.fiverr.com', 'www.lni.wa.gov', 'www.enterprise.gov.ie', 'developer.wordpress.org', 'developer.mozilla.org', 'www.onetonline.org', 'understand-digital-data-roles-skills.service.gov.uk', 'www.findapprenticeship.service.gov.uk', 'www.ncs.gov.in', 'www.gov.ie', 'www.publicjobs.ie', 'www.csa.ie', 'www.cso.ie', 'www.seattlepipetrades.org', 'ualocal32.com', 'www.plu68.com', 'www.plumbers98tc.org', 'lni.wa.gov', 'tsbpe.texas.gov', 'www.michigan.gov', 'cle33sheetmetal.org', 'sheetmetaltraining.org', 'sheetmetalapprenticetraining.com', 'www.smw10.org', 'dli.mn.gov', 'dol.ny.gov', 'www.ieetc.org', 'www.laett.com', 'www.ualocal364.com', 'www.wsctf.org', 'www.cnoett.org', 'www.ua373.com', 'neieprecruitment.org', 'iueclocal4.com', 'nycironworkers.org', 'www.ironworkers580.org', 'www.ironworkers12.org', 'chicago.taleo.net', 'www.transitchicago.com', 'www.cps.edu', 'ides.illinois.gov', 'illinoisjoblink.illinois.gov', 'www.houstontx.gov', 'www.governmentjobs.com', 'jobs.ridemetro.org', 'hrrm.harriscountytx.gov', 'www.workintexas.com', 'www.seattle.gov', 'www.washington.edu', 'hfs.uw.edu', 'www.worksourcewa.com', 'www.electricaltrainingalliance.org', 'www.etasv.org', 'nietc.org', 'www.oregon.gov', 'www.laborerstrainingschool.com', 'sm88jatc.org', 'smw20training.com', 'www.smart18ecwi.org', 'www.socalsma.org', 'www.sheetmetal-iti.org', 'ua.org', 'www.pftf597.org', 'www.ua190.org', 'www.ualocal467.org', 'sajac-boilermakers.org', 'boilermaker.ca', '555.boilermaker.ca', 'www.dir.ca.gov', 'boilermakers.org', 'www.bnap.com', 'www.liuna.org', 'www.liunatraining.org', 'www.njlaborers.org', 'liunaminnesota.org', 'www.iuoe.org', 'local150.org', 'www.local49training.org', 'www.apprenticeship.gov', 'www.osha.gov', 'openstax.org',
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
        TruckDriverJobsDallasCdlRequirementsTrainingBlogSeeder::class,
        PetrolPumpAttendantJobsLahoreDutiesSalaryBlogSeeder::class,
        SweeperJobsKarachiGovernmentPrivateOptionsBlogSeeder::class,
        JanitorJobsPhiladelphiaNoExperienceBlogSeeder::class,
        DeliveryDriverJobsLosAngelesAppsRequirementsBlogSeeder::class,
        GasStationJobsHoustonHiringAgeRequirementsBlogSeeder::class,
        ChefJobsParisFranceEntryPathsBlogSeeder::class,
        WaiterJobsDohaQatarRequirementsInterviewTipsBlogSeeder::class,
        HotelHousekeepingJobsLasVegasNoExperienceBlogSeeder::class,
        HotelJobsDubaiForeignersRolesRequirementsBlogSeeder::class,
        HotelReceptionistJobsOrlandoFloridaBlogSeeder::class,
        PharmacyTechnicianJobsColumbusOhioRequirementsBlogSeeder::class,
        HomeHealthAideJobsBrooklynNewYorkTrainingHiringBlogSeeder::class,
        LabTechnicianJobsLahorePunjabQualificationsSalaryBlogSeeder::class,
        MedicalAssistantJobsPhoenixNoExperienceBlogSeeder::class,
        CaregiverJobsCalgaryVisaSponsorshipBlogSeeder::class,
        NursingJobsTorontoForeignTrainedNursesBlogSeeder::class,
        CnaJobsAtlantaGeorgiaCertificationPayBlogSeeder::class,
        NursingJobsHoustonLicensingEntryRequirementsBlogSeeder::class,
        NurseJobsLondonOverseasRegistrationBlogSeeder::class,
        NursingJobsRiyadhRequirementsContractBasicsBlogSeeder::class,
        PrisonOfficerJobsLondonRequirementsSelectionBlogSeeder::class,
        CustomsOfficerJobsVancouverHowToApplyBlogSeeder::class,
        FirefighterJobsChicagoApplicationPhysicalTestBlogSeeder::class,
        ParamedicJobsTorontoTrainingCertificationBlogSeeder::class,
        SecurityGuardJobsDubaiNoExperienceBlogSeeder::class,
        SecurityGuardJobsHoustonLicenseTrainingBlogSeeder::class,
        PoliceConstableJobsPunjabPakistanBlogSeeder::class,
        PoliceConstableRecruitmentUttarPradeshBlogSeeder::class,
        HowToFindJobAbroadFromPakistan2026BlogSeeder::class,
        LivingWorkingAbroad2026MinimumWageCostOfLivingBlogSeeder::class,
        WorkVisasPakistanis2026CountryGuideBlogSeeder::class,
        OfficeAssistantJobsGlasgowEntryGuideBlogSeeder::class,
        BackOfficeExecutiveJobsDelhiDutiesSalaryBlogSeeder::class,
        ReceptionistJobsDubaiRequirementsInterviewTipsBlogSeeder::class,
        CallCentreJobsKarachiSindhFreshersBlogSeeder::class,
        BpoJobsPuneWhatToExpectDayOneBlogSeeder::class,
        DataEntryJobsHoustonNoExperienceBlogSeeder::class,
        DataEntryJobsHomePennsylvaniaLegitRedFlagsBlogSeeder::class,
        SocialMediaManagerJobsFromHomeMelbourneBlogSeeder::class,
        SeoExecutiveJobsMumbaiSalarySkillsBlogSeeder::class,
        ContentWriterJobsLahoreHowToGetHiredBlogSeeder::class,
        TranscriptionJobsFromHomeOhioBlogSeeder::class,
        WorkFromHomeJobsMichiganNoExperienceBlogSeeder::class,
        RemoteJobsBeginnersIslamabadPakistanBlogSeeder::class,
        CopywriterJobsBirminghamEnglandBeginnersBlogSeeder::class,
        EmailMarketingJobsVancouverBritishColumbiaBlogSeeder::class,
        AffiliateMarketingBeginnersKarachiSindhBlogSeeder::class,
        EntryLevelSeoJobsNewYorkCityBlogSeeder::class,
        RemoteSeoJobsDenverColoradoBlogSeeder::class,
        PpcSpecialistJobsAtlantaSkillsCertificationsBlogSeeder::class,
        RemoteCustomerSupportJobsFloridaBlogSeeder::class,
        BugBountyHuntingKarachiSindhBeginnersBlogSeeder::class,
        VirtualAssistantJobsManilaPhilippinesBlogSeeder::class,
        OnlineTutoringJobsNewJerseyBlogSeeder::class,
        FreelanceWritingJobsNairobiKenyaBlogSeeder::class,
        SoftwareDeveloperJobsSeattleWashingtonBlogSeeder::class,
        FrontEndDeveloperJobsManchesterEnglandBlogSeeder::class,
        DataAnalystJobsDublinIrelandBlogSeeder::class,
        WordPressDeveloperJobsAustinTexasBlogSeeder::class,
        RemoteWordPressDeveloperJobsTorontoOntarioBlogSeeder::class,
        JuniorWebDeveloperJobsChicagoNoDegreeBlogSeeder::class,
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
            if (in_array($slug, ['carpenter-jobs-in-usa', 'virtual-assistant-jobs-in-pakistan', 'remote-frontend-developer-jobs-in-usa', 'on-page-seo-assistant-jobs', 'digital-marketing-jobs-in-usa', 'remote-jobs-in-usa', 'copywriter-jobs-in-usa', 'marketing-jobs-in-uk', 'data-entry-jobs-in-usa', 'social-media-manager-jobs-in-usa', 'content-writer-jobs-in-usa', 'how-to-get-a-remote-customer-service-job-with-no-experience', 'online-jobs-in-pakistan', 'online-jobs-in-usa-for-pakistanis-remote-work', 'remote-jobs-in-pakistan-with-no-experience', 'security-guard-jobs-in-the-uae-uk-and-canada', 'how-to-get-hotel-housekeeper-job-usa-from-pakistan-2026', 'hotel-housekeeper-jobs-usa-visa-sponsorship-2026', 'hotel-housekeeper-salary-usa-2026', 'hotel-jobs-in-usa-for-foreigners', 'hotel-and-restaurant-jobs-in-the-uae-and-italy', 'airport-ground-staff-jobs-in-dubai', 'clinic-receptionist-jobs-in-usa', 'remote-customer-support-jobs-florida', 'kitchen-porter-jobs-uk-visa-sponsorship-2026', 'receptionist-jobs-dubai-requirements-interview-tips', 'security-guard-jobs-dubai-no-experience', 'cleaner-and-janitor-jobs-with-visa-sponsorship', 'truck-driver-salary-usa-2026', 'how-to-get-warehouse-worker-job-usa-from-pakistan-2026', 'security-guard-jobs-houston-license-training', 'nursing-jobs-houston-licensing-entry-requirements'], true)) {
                continue;
            }

            expect($slugs)->toContain($slug);
        }
    }
});
