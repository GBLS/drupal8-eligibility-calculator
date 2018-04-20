// Unused
const incomeTable = {
    1: 15175,
    2: 20575,
    3: 25975,
    4: 31375,
    5: 36775,
    6: 42175,
    7: 47575,
    8: 52975,
    over: 5400,
};

const incomeBase = 12140;
const incomeIncrement = 4320;

const zips = [
  "02108",
  "02109",
  "02110",
  "02111",
  "02112",
  "02113",
  "02114",
  "02115",
  "02116",
  "02117",
  "02118",
  "02119",
  "02120",
  "02121",
  "02122",
  "02123",
  "02124",
  "02125",
  "02126",
  "02127",
  "02128",
  "02129",
  "02130",
  "02131",
  "02132",
  "02133",
  "02134",
  "02135",
  "02136",
  "02137",
  "02150",
  "02151",
  "02152",
  "02163",
  "02196",
  "02199",
  "02201",
  "02203",
  "02204",
  "02205",
  "02206",
  "02210",
  "02211",
  "02212",
  "02215",
  "02217",
  "02222",
  "02241",
  "02266",
  "02283",
  "02284",
  "02293",
  "02297",
  "02298",
  "01718",
  "01720",
  "02474",
  "02476",
  "01730",
  "02478",
  "01719",
  "02184",
  "02185",
  "02445",
  "02446",
  "02447",
  "02138",
  "02139",
  "02140",
  "02141",
  "02142",
  "02238",
  "02021",
  "01741",
  "02025",
  "01742",
  "02149",
  "01451",
  "02043",
  "02044",
  "02343",
  "02045",
  "02420",
  "02421",
  "01773",
  "01460",
  "02148",
  "01754",
  "02153",
  "02155",
  "02176",
  "02186",
  "02187",
  "02458",
  "02459",
  "02461",
  "02462",
  "02464",
  "02460",
  "02495",
  "02156",
  "02466",
  "02467",
  "02475",
  "01864",
  "01889",
  "02061",
  "02169",
  "02170",
  "02171",
  "02269",
  "02368",
  "01867",
  "02066",
  "02143",
  "02144",
  "02145",
  "02180",
  "01775",
  "01880",
  "02451",
  "02452",
  "02453",
  "02454",
  "02471",
  "02472",
  "02477",
  "02188",
  "02043",
  "02189",
  "02190",
  "02191",
  "01887",
  "01890",
  "01801",
  "01813",
  "01815",
  "01888"
];

function checkIncome(householdSize, income, period, povertyMultiplier) {
    var householdSize = parseInt(householdSize);
    var income = parseInt(income.replace(/,/g,''));

    var multiplier = 1;
    switch (period) {
        case "weekly":
            multiplier = 52;
            break;
        case "biweekly":
            multiplier = 26;
            break;
        case "monthly":
            multiplier = 12;
            break;
        case "annually":
            multiplier = 1;
            break;
        default:
            throw ("Can't understand the provided income period");
    }
    /*
    if (householdSize < 8) {
        return income * multiplier <= incomeTable[householdSize];
    } else {
        var overageMembers = householdSize - 8;
        var overageLimit = (overageMembers * incomeTable['over']) + incomeTable[8];
        return income * multiplier <= overageLimit;
    }
    */
    var annualizedIncome = income * multiplier;
    var fudgeFactor = 1.01; // 1% fudge factor to avoid cliff that doesn't reflect our true policies
    var limit = (incomeBase + ((householdSize - 1) * incomeIncrement)) * povertyMultiplier * fudgeFactor 

    return annualizedIncome <= limit;

}

function checkZip(zip) {
    return zips.indexOf(zip) != -1;
}

function checkForm() {

    hide();
    var formObject = document.getElementById('eligibilityform');
    var inServiceArea = checkZip(formObject.zip.value);
    var incomeEligible125 = checkIncome(formObject.householdSize.value, formObject.income.value, formObject.period.value, 1.25);
    var incomeEligible200 = checkIncome(formObject.householdSize.value, formObject.income.value, formObject.period.value, 2);
    var incomeEligible = incomeEligible200;
    var isElder = formObject.iselder.checked;
    var isMedicare = formObject.ismedicare.checked;
    var isMedicareExt = formObject.ismedicare_ext.checked;
    var isVictim = formObject.isvictim.checked;


    if ( inServiceArea && incomeEligible125) {
        document.getElementById('eligible-125').style.display = "block";
    } else if ( inServiceArea && incomeEligible200 )  {
        document.getElementById('eligible-200').style.display = "block";
    } else if ( (inServiceArea && isElder) || (inServiceArea && isMedicare) || isMedicareExt || (inServiceArea && isVictim) ) {
        document.getElementById('eligible').style.display = "block";
    } else {
        document.getElementById('ineligible').style.display = "block";
    }
    if (!inServiceArea && !isMedicareExt) {
        document.getElementById('outside_service_area').style.display = "block";
    }
    if (!incomeEligible && !isElder && !isMedicare && !isVictim && !isMedicareExt) {
        document.getElementById('income_too_high').style.display = "block";
    }
}

function isEligible(householdSize, income, period, zip) {
    return checkIncome(householdSize, income, period) && checkZip(zip);
}

function hide() {
    document.getElementById('eligible').style.display = "none";
    document.getElementById('eligible-125').style.display = "none";
    document.getElementById('eligible-200').style.display = "none";
    document.getElementById('ineligible').style.display = "none";
    document.getElementById('outside_service_area').style.display = "none";
    document.getElementById('income_too_high').style.display = "none";
}

window.addEventListener('load', hide);