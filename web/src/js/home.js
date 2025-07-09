function toggleAccordion(id) {
  const content = document.getElementById(id);
  const toggle = document.getElementById(id + "Toggle");
  if (content.classList.contains("hidden")) {
    content.classList.remove("hidden");
    toggle.style.transform = "rotate(180deg)";
  } else {
    content.classList.add("hidden");
    toggle.style.transform = "rotate(0deg)";
  }
}



function showTab(tabId) {
  const tabs = document.querySelectorAll(".tab-content");
  tabs.forEach((tab) => tab.classList.add("hidden"));

  const buttons = document.querySelectorAll("button");
  buttons.forEach((button) =>
    button.classList.remove("bg-blue-600", "text-white")
  );
  buttons.forEach((button) =>
    button.classList.add("bg-gray-200", "text-gray-800")
  );

  document.getElementById(tabId).classList.remove("hidden");
  event.target.classList.remove("bg-gray-200", "text-gray-800");
  event.target.classList.add("bg-blue-600", "text-white");
}


const startTime = document.getElementById("startTime");
const breakStartTime = document.getElementById("breakStartTime");
const breakEndTime = document.getElementById("breakEndTime");
const workDuration = document.getElementById("workDuration");
const resultTime = document.getElementById("resultTime");
const pauseDuration = document.getElementById("pauseDuration");

const matinEnabled = document.getElementById("matinEnabled");
const apresMidiEnabled = document.getElementById("apresMidiEnabled");

const heuresValidesMatin = document.getElementById("matinValidatedHours");
const heuresValidesApresMidi = document.getElementById("apresMidiValidatedHours");

function calculateEndTime() {
  saveSettings(); // Save settings before calculation
  const start = new Date();
  start.setHours(startTime.value.split(':')[0], startTime.value.split(':')[1], 0, 0);
  const breakStart = new Date();
  breakStart.setHours(breakStartTime.value.split(':')[0], breakStartTime.value.split(':')[1], 0, 0);
  const breakEnd = new Date();
  breakEnd.setHours(breakEndTime.value.split(':')[0], breakEndTime.value.split(':')[1], 0, 0);
  const workDurationValue = new Date();
  workDurationValue.setHours(workDuration.value.split(':')[0], workDuration.value.split(':')[1], 0, 0);
  const pauseDurationValue = new Date();
  pauseDurationValue.setHours(pauseDuration.innerText.split(':')[0], pauseDuration.innerText.split(':')[1], 0, 0);

  const matinEnabledValue = matinEnabled.checked;

  // Calcul heures validées matin (en minutes)
  let matinMinutes = 0;
  //console.log(start.getTime(), breakStart.getTime());
  if (!isNaN(start.getTime()) && !isNaN(breakStart.getTime())) {
    matinMinutes = Math.max(0, Math.round((breakStart - start) / 60000));
    //console.log(`Matin minutes: ${matinMinutes}`);
    const h = Math.floor(matinMinutes / 60);
    const m = matinMinutes % 60;
    //console.log(`Matin hours: ${h}, minutes: ${m}`);
    heuresValidesMatin.innerText = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
  } else {
    heuresValidesMatin.innerText = "00:20";
  }

  // Calcul du temps restant à faire l'après-midi (en minutes)
  let apresMidiMinutes = 0;
  let endTime;
  if (!isNaN(breakEnd.getTime())) {
    console.log("work duration valuejjj:", workDurationValue);
    console.log("work duration value:", workDuration.value);
    let remainingWork = workDurationValue.getHours() * 60 + workDurationValue.getMinutes();
    console.log(`Remaining work before morning: ${remainingWork} minutes`);
    if (matinEnabledValue) {
      console.log(remainingWork, matinMinutes);
      remainingWork = Math.max(0, remainingWork - matinMinutes);
      console.log(`morning enabled: ${matinEnabledValue}, remaining work: ${remainingWork} minutes`);
    }
    // Ajout de la pause si renseignée
    endTime = new Date(breakEnd.getTime() + remainingWork * 60000);
    apresMidiMinutes = remainingWork;
    console.log(`Remaining work after morning: ${remainingWork} minutes`);
    console.log(`endTime: ${endTime.toLocaleString()}`);
    // Calcul heures validées après-midi (en minutes)
    const h = Math.floor(apresMidiMinutes / 60);
    const m = apresMidiMinutes % 60;
    console.log(`Après-midi hours: ${h}, minutes: ${m}`);
    heuresValidesApresMidi.innerText = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
  } else {
    heuresValidesApresMidi.innerText = "00:00";
    endTime = null;
  }

  console.log(`End time: ${endTime ? endTime.toLocaleString() : "N/A"}`);
  if (endTime && !isNaN(endTime.getTime())) {
    resultTime.textContent = `End Time: ${endTime.getHours().toString().padStart(2, '0')}:${endTime.getMinutes().toString().padStart(2, '0')}`;
  } else {
    resultTime.textContent = "Please enter valid times.";
  }
}

function saveSettings() {
  const settings = {
    startTime: startTime.value,
    breakStartTime: breakStartTime.value,
    breakEndTime: breakEndTime.value,
    workDuration: workDuration.value,
    pauseDuration: pauseDuration.value,
    matinEnabled: matinEnabled.checked,
    apresMidiEnabled: apresMidiEnabled.checked
  };

  localStorage.setItem("workSettings", JSON.stringify(settings));
  //alert("Settings saved successfully!");
}

function loadSettings() {
  const settings = JSON.parse(localStorage.getItem("workSettings"));
  if (settings) {
    startTime.value = settings.startTime || "08:00";
    breakStartTime.value = settings.breakStartTime || "12:00";
    breakEndTime.value = settings.breakEndTime || "13:00";
    workDuration.value = settings.workDuration || "480"; // Default to 8 hours
    pauseDuration.value = settings.pauseDuration || "20"; // Default to 20 minutes
    matinEnabled.checked = settings.matinEnabled !== undefined ? settings.matinEnabled : true;
    apresMidiEnabled.checked = settings.apresMidiEnabled !== undefined ? settings.apresMidiEnabled : true;
    calculateEndTime(); // Recalculate end time with loaded settings
  } else {
    // Set default values if no settings are found
    startTime.value = "08:00";
    breakStartTime.value = "12:00";
    breakEndTime.value = "13:00";
    workDuration.value = "07:00"; // Default to 8 hours
    pauseDuration.value = "20"; // Default to 20 minutes
    matinEnabled.checked = true;
    apresMidiEnabled.checked = true;
  }
  calculateEndTime(); // Initial calculation to set the end time display
}
// Load settings on page load
document.addEventListener("DOMContentLoaded", () => {
  loadSettings();
});

//document.getElementById("calculateButton").addEventListener("click", calculateEndTime);
startTime.addEventListener("change", calculateEndTime);
breakStartTime.addEventListener("change", calculateEndTime);
breakEndTime.addEventListener("change", calculateEndTime);
workDuration.addEventListener("input", calculateEndTime);
pauseDuration.addEventListener("input", calculateEndTime);

matinEnabled.addEventListener("change",calculateEndTime);
apresMidiEnabled.addEventListener("change",calculateEndTime);