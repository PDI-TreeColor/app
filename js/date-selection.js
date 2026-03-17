function removeSelectOptions(selectElement) {
    var i, L = selectElement.options.length - 1;
    for(i = L; i >= 0; i--) {
        selectElement.remove(i);
    }
}

function updateAvailableMonths(year) {
    removeSelectOptions(monthSelect);
    var lastMonthAvailable = 12
    if (year == todayYear) {
        lastMonthAvailable = todayMonth;
    }
    for (let i = 1; i<=lastMonthAvailable; i++) {
        var opt = document.createElement("option");
        opt.value = i.toString()
        opt.text = i.toString()
        monthSelect.add(opt, null)
    }
}

function updateAvailableYears() {
    for (let i = 2016; i<=todayYear; i++) {
        var opt = document.createElement("option");
        opt.value = i.toString()
        opt.text = i.toString()
        yearSelect.add(opt, null)
    }
}

const yearSelect = document.getElementById("year-select");
const monthSelect = document.getElementById("month-select");

let today = new Date().toJSON().slice(0,7)
let todayYear = Number(today.slice(0,4))
let todayMonth = Number(today.slice(5,7))

updateAvailableYears();
yearSelect.value = todayYear;

updateAvailableMonths(todayYear);
monthSelect.value = todayMonth;

yearSelect.addEventListener("change", (event) => {
    year = Number(event.target.value);
    updateAvailableMonths(year);
});