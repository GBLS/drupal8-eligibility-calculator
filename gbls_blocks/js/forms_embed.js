var incomeBase = parseInt(document.getElementById('poverty_base').value)
var incomeIncrement = parseInt(document.getElementById('poverty_increment').value)

var poverty_multiplier_1  = parseInt(document.getElementById('poverty_multiplier_1').value ) / 100
var poverty_multiplier_2 = parseInt(document.getElementById('poverty_multiplier_2').value) / 100

var zips = document.getElementById('coverage_zips').value

//const incomeBase = 12140;
//const incomeIncrement = 4320;



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

    var annualizedIncome = income * multiplier;
    console.log("Annual income is " + annualizedIncome);
    var fudgeFactor = 1.01; // 1% fudge factor to avoid cliff that doesn't reflect our true policies
    var limit = (incomeBase + ((householdSize - 1) * incomeIncrement)) * povertyMultiplier * fudgeFactor 
    console.log("Limit is " + limit)
    var qualifies = annualizedIncome <= limit;
    console.log("Qualifies: " +  qualifies)

    return qualifies;

}

function checkZip(zip) {
    return zips.indexOf(zip) != -1;
}

function formIsComplete(formObject) {
    var elements = formObject.elements;
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].required && (elements[i].value == '' || typeof elements[i] == 'undefined')) {
            return false;
        }
    }
    return true;
}

function checkForm() {

    hide();

    var formObject = document.getElementById('eligibilityform');
    if (!formIsComplete(formObject)) {
        return // don't do any validation if the form is empty
    }

    var inServiceArea = checkZip(formObject.zip.value);
    var incomeEligible125 = checkIncome(formObject.householdSize.value, formObject.income.value, formObject.period.value, poverty_multiplier_1);
    var incomeEligible200 = checkIncome(formObject.householdSize.value, formObject.income.value, formObject.period.value, poverty_multiplier_2);
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