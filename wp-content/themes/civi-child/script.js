'use strict';
var interval;
var topbarspan = document.querySelectorAll(".right-top-bar span")
for (let span of topbarspan) {
	const newLink = document.createElement('a');
	newLink.href = '#';
	newLink.innerHTML = span.innerHTML;
	span.parentNode.replaceChild(newLink, span);
}
document.querySelector(".right-top-bar a:nth-child(2)").href = "/.website_e2445dff/coming-soon/"
document.querySelector(".right-top-bar a:nth-child(2) i").classList.remove("fa-envelope")
document.querySelector(".right-top-bar a:nth-child(2) i").classList.add("fa-briefcase")
document.querySelector(".right-top-bar a:nth-child(1)").href = "/.website_e2445dff/guides/"
document.querySelector(".right-top-bar a:nth-child(1) i").classList.remove("fa-phone")
document.querySelector(".right-top-bar a:nth-child(1) i").classList.add("fa-graduation-cap")
document.querySelector(".right-top-bar a:nth-child(2)").style.marginLeft = "25px"
var postJobLogin =  document.querySelector("header .right-header a")
if (postJobLogin.innerText === "Login") {
	var postJob = document.querySelectorAll(".civi-button.add-job");
	for (let namepj of postJob) {
    	namepj.setAttribute("href", "#popup-form");
		namepj.classList.add("btn-login");
		namepj.parentNode.classList.add("logged-out")
	}
}

var allSerchInput = document.querySelectorAll("form.form-jobs-top-filter input");
if (allSerchInput) {
	for (let input of allSerchInput) {
    	input.setAttribute("required", "")
	}
}
var allSerchInput = document.querySelectorAll("form.form-search-horizontal input");
if (allSerchInput) {
	for (let input of allSerchInput) {
    	input.setAttribute("required", "")
	}
}
var submitForm = document.querySelector("#submit_company_form");
if (allSerchInput) {
	document.querySelector("#submit_company_form").addEventListener("submit", function (e) {
    	e.preventDefault()
	})
}
function topScroll() {
	document.querySelector(".preview-job-wrapper").scrollTop = 0
}
function toTopScroll(){
var layout = document.querySelectorAll("div.content-jobs.area-jobs.area-archive.column-1 > div.civi-jobs-item.layout-list");
for (let name of layout) {
    name.setAttribute("onclick", "topScroll()")
}
}
toTopScroll()
function addCheckListener() {
	var pageButtons = document.querySelectorAll(".pagination a");
	for (let button of pageButtons) {
	button.setAttribute("onclick", "startCheck()")
}
}
addCheckListener()
function checkItems() {
    var element = document.querySelector("div.content-jobs.area-jobs.area-archive.column-1 > div.civi-jobs-item.layout-list");
    if (element) {
        clearInterval(interval);
		toTopScroll();
		addCheckListener();
    }
}
function startCheck() {
	var interval = setInterval(checkItems, 500);
}
var c =document.querySelector("body.home .filter-warpper").closest("section")
c.style.zIndex = '7';
const container = document.querySelector("body.home .civi-jobs .elementor-grid");
container.closest("section").style.zIndex = '7';
function blockNewElements(event) {
	const newElement = event.target;
	if (newElement.parentNode === container) {
		container.removeChild(newElement);
		console.log('New element blocked:', newElement);
	}
}
container.addEventListener('DOMNodeInserted', blockNewElements)