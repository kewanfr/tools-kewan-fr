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