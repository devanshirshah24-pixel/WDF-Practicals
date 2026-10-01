  
let loginform=document.getElementById("loginform");

if (loginform)
{
    loginform.addEventListener("submit", function(event)
    {
        let username=document.getElementById("username");
        let password=document.getElementById("password");
        let message=document.getElementById("message");
        event.preventDefault();

        if(username.value=="")
        {
            message.innerHTML= "Please enter the username";
            message.style.color="Blue";
            return;
        }

        if(password.value=="")
        {
            message.innerHTML="Please enter the Password";
            return;
        }
        message.innerHTML="Login Succesfully!";    
    });

}

let signupform=document.getElementById("signupform");

if(signupform)
{
    // Field references
    let fullname=document.getElementById("fullname");
    let email=document.getElementById("email");
    let mobile=document.getElementById("mobile");
    let course=document.getElementById("course");
    let year=document.getElementById("year");
    let crpassword=document.getElementById("createpassword");
    let copassword=document.getElementById("confirmpassword");
    let terms=document.getElementById("terms");
    let signupMessage=document.getElementById("message");

    // Regular expressions
    let namePattern=/^[A-Za-z\s]{3,}$/;
    let emailPattern=/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    let mobilePattern=/^[6-9][0-9]{9}$/;
    let passwordPattern=/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

    // Helper to show/clear a single field's error message
    function setError(fieldId, msg)
    {
        let errorEl=document.getElementById(fieldId+"Error");
        if(errorEl) errorEl.innerHTML=msg;
    }

    function clearAllErrors()
    {
        document.querySelectorAll("#signupform .error").forEach(function(el)
        {
            el.innerHTML="";
        });
    }

    signupform.addEventListener("submit", function(event)
    {
        event.preventDefault();
        clearAllErrors();

        let valid=true;
        let selectedGender=document.querySelector('input[name="gender"]:checked');

        // Name validation
        if(!namePattern.test(fullname.value.trim()))
        {
            setError("fullname","Name must contain only alphabets/spaces and be at least 3 characters.");
            valid=false;
        }

        // Email validation
        if(!emailPattern.test(email.value.trim()))
        {
            setError("email","Please enter a valid email address.");
            valid=false;
        }

        // Mobile validation
        if(!mobilePattern.test(mobile.value.trim()))
        {
            setError("mobile","Mobile number must be exactly 10 digits and start with 6, 7, 8 or 9.");
            valid=false;
        }

        // Course validation
        if(course.value=="")
        {
            setError("course","Please select a course.");
            valid=false;
        }

        // Year validation
        if(year.value=="")
        {
            setError("year","Please select your year.");
            valid=false;
        }

        // Gender validation
        if(!selectedGender)
        {
            setError("gender","Please select your gender.");
            valid=false;
        }

        // Password validation
        if(!passwordPattern.test(crpassword.value))
        {
            setError("createpassword","Password must be 8+ characters with uppercase, lowercase, number and special character.");
            valid=false;
        }

        // Confirm password validation
        if(copassword.value=="")
        {
            setError("confirmpassword","Please confirm the password.");
            valid=false;
        }
        else if(crpassword.value!=copassword.value)
        {
            setError("confirmpassword","Passwords do not match.");
            valid=false;
        }

        // Terms validation
        if(!terms.checked)
        {
            setError("terms","You must accept the Terms and Conditions.");
            valid=false;
        }

        if(!valid)
        {
            signupMessage.innerHTML="Please fix the highlighted errors.";
            signupMessage.style.color="#d32f2f";
            return;
        }

        signupMessage.style.color="green";
        signupMessage.innerHTML="Account Created Successfully!";
    });
}

let wdf=document.getElementById("wdf");
let cpp=document.getElementById("cpp");
let dsa=document.getElementById("dsa");

function checkAttendance(attendance,statusId)
{
    let percentage=parseInt(attendance.innerHTML)
    
    if(percentage>=75)
    {
        document.getElementById(statusId).innerHTML="Good Attendance";
         document.getElementById(statusId).style.color="green";
    }
    else
    {
         document.getElementById(statusId).innerHTML="You need to attend the classes regularly";
         document.getElementById(statusId).style.color="red";    
    }
}



function calculateGrade(percentage,gradeId)
{
    let marks=parseInt(percentage.innerHTML)


    if(marks>=90)
    {
         document.getElementById(gradeId).innerHTML="A+";
         document.getElementById(gradeId).style.color="green";
    }
    else if(marks>=80)
    {
          document.getElementById(gradeId).innerHTML="A";
         document.getElementById(gradeId).style.color="green";
    }
    else if(marks>=70)
    {
          document.getElementById(gradeId).innerHTML="B";
         document.getElementById(gradeId).style.color="green";
    }
    else
    {
          document.getElementById(gradeId).innerHTML="C";
         document.getElementById(gradeId).style.color="green";
    }
   
}

if(document.getElementById("wdfStatus"))
{
    
    checkAttendance(wdf,"wdfStatus");
    checkAttendance(cpp,"cppStatus");
    checkAttendance(dsa,"dsaStatus");
}

if(document.getElementById("wdfgrade"))
{
    calculateGrade(wdf,"wdfgrade");
    calculateGrade(cpp,"cppgrade");
    calculateGrade(dsa,"dsagrade"); 
}

function openModal() {

    document.getElementById("courseModal").style.display = "block";

}

function closeModal() {

    document.getElementById("courseModal").style.display = "none";

}

// Close the course modal when clicking outside of it
window.addEventListener("click", function(event)
{
    let modal = document.getElementById("courseModal");
    if (modal && event.target === modal)
    {
        modal.style.display = "none";
    }
});


//   Light / Dark Theme Switcher

let themeToggle = document.getElementById("themeToggle");

// Apply saved theme on page load
if (localStorage.getItem("theme") === "dark")
{
    document.body.classList.add("dark-mode");
    if (themeToggle) themeToggle.innerHTML = "☀️";
}

if (themeToggle)
{
    themeToggle.addEventListener("click", function()
    {
        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode"))
        {
            localStorage.setItem("theme", "dark");
            themeToggle.innerHTML = "☀️";
        }
        else
        {
            localStorage.setItem("theme", "light");
            themeToggle.innerHTML = "🌙";
        }
    });
}

  //  Hamburger Menu
  
let hamburger = document.getElementById("hamburger");
let navMenu = document.getElementById("navMenu");

if (hamburger && navMenu)
{
    hamburger.addEventListener("click", function()
    {
        navMenu.classList.toggle("active");
    });
}



 //   Notification Banner
   
let notificationBanner = document.getElementById("notificationBanner");
let closeBanner = document.getElementById("closeBanner");

if (notificationBanner && closeBanner)
{
    // Keep the banner closed for the rest of the session once dismissed
    if (sessionStorage.getItem("bannerClosed") === "true")
    {
        notificationBanner.style.display = "none";
    }

    closeBanner.addEventListener("click", function()
    {
        notificationBanner.style.display = "none";
        sessionStorage.setItem("bannerClosed", "true");
    });
}

  // Collapsible FAQ

let faqQuestions = document.querySelectorAll(".faq-question");

faqQuestions.forEach(function(btn)
{
    btn.addEventListener("click", function()
    {
        let answer = btn.nextElementSibling;
        answer.classList.toggle("show");

        let icon = btn.querySelector(".faq-icon");
        if (icon)
        {
            icon.innerHTML = answer.classList.contains("show") ? "−" : "+";
        }
    });
});

 //   Content / Image Slider

let slides = document.querySelectorAll(".slide");
let nextSlideBtn = document.getElementById("nextSlide");
let prevSlideBtn = document.getElementById("prevSlide");
let currentSlide = 0;
let slideTimer;

function showSlide(index)
{
    slides.forEach(function(slide)
    {
        slide.classList.remove("active");
    });
    slides[index].classList.add("active");
}

function startSlider()
{
    slideTimer = setInterval(function()
    {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 5000);
}

if (nextSlideBtn && prevSlideBtn && slides.length > 0)
{
    nextSlideBtn.addEventListener("click", function()
    {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
        clearInterval(slideTimer);
        startSlider();
    });

    prevSlideBtn.addEventListener("click", function()
    {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
        clearInterval(slideTimer);
        startSlider();
    });

    startSlider();
}

// Json

// Helper: Generic Fetch Function with Error Handling & LocalStorage Caching
async function fetchJSONData(filename) {
    const cacheKey = "cache_" + filename;
    try {
        const response = await fetch(filename);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        // Advanced Extension: Cache response in localStorage
        localStorage.setItem(cacheKey, JSON.stringify(data));
        return data;
    } catch (error) {
        console.warn(`Fetch failed for ${filename}, attempting cache load:`, error);
        const cached = localStorage.getItem(cacheKey);
        if (cached) {
            return JSON.parse(cached);
        }
        return [];
    }
}

//  Events Slider & Search (Home.html)

const eventsSlider = document.getElementById("eventsSlider");
if (eventsSlider) {
    const slideContent = document.getElementById("slideContent");
    const eventsLoading = document.getElementById("eventsLoading");
    const searchInput = document.getElementById("eventSearch");
    const categoryFilter = document.getElementById("eventCategoryFilter");

    let eventsData = [];
    let filteredEvents = [];
    let eventIndex = 0;

    function renderSlide(index) {
        if (filteredEvents.length === 0) {
            slideContent.innerHTML = `<h3>No Events Found</h3><p>Try adjusting your search criteria.</p>`;
            return;
        }
        const item = filteredEvents[index];
        slideContent.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
            <p><small><b>Category:</b> ${item.category} | <b>Date:</b> ${item.date}</small></p>
        `;
    }

    function applyEventFilters() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : "";
        const category = categoryFilter ? categoryFilter.value : "All";

        filteredEvents = eventsData.filter(item => {
            const matchesQuery = item.title.toLowerCase().includes(query) || item.description.toLowerCase().includes(query);
            const matchesCat = category === "All" || item.category === category;
            return matchesQuery && matchesCat;
        });

        eventIndex = 0;
        renderSlide(eventIndex);
    }

    fetchJSONData("events.json").then(data => {
        eventsData = data;
        filteredEvents = [...eventsData];
        if (eventsLoading) eventsLoading.style.display = "none";
        eventsSlider.style.display = "block";
        renderSlide(eventIndex);

        if (searchInput) searchInput.addEventListener("input", applyEventFilters);
        if (categoryFilter) categoryFilter.addEventListener("change", applyEventFilters);

        document.getElementById("nextSlide").addEventListener("click", () => {
            if (filteredEvents.length > 0) {
                eventIndex = (eventIndex + 1) % filteredEvents.length;
                renderSlide(eventIndex);
            }
        });

        document.getElementById("prevSlide").addEventListener("click", () => {
            if (filteredEvents.length > 0) {
                eventIndex = (eventIndex - 1 + filteredEvents.length) % filteredEvents.length;
                renderSlide(eventIndex);
            }
        });
    });
}

//  Student Directory: Search, Filter, Sort & Pagination (Settings.html)

const studentTableBody = document.getElementById("studentTableBody");
if (studentTableBody) {
    const studentLoading = document.getElementById("studentLoading");
    const searchInput = document.getElementById("studentSearch");
    const courseFilter = document.getElementById("courseFilter");
    const sortSelect = document.getElementById("sortStudents");
    const paginationContainer = document.getElementById("studentPagination");

    let studentsData = [];
    let processedStudents = [];
    let currentPage = 1;
    const rowsPerPage = 5;

    function renderStudentTable() {
        studentTableBody.innerHTML = "";

        if (processedStudents.length === 0) {
            studentTableBody.innerHTML = `<tr><td colspan="5">No student records match your criteria.</td></tr>`;
            paginationContainer.innerHTML = "";
            return;
        }

        // Pagination calculation
        const startIndex = (currentPage - 1) * rowsPerPage;
        const pageItems = processedStudents.slice(startIndex, startIndex + rowsPerPage);

        pageItems.forEach(student => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${student.id}</td>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.course}</td>
                <td>Year ${student.year}</td>
            `;
            studentTableBody.appendChild(tr);
        });

        renderPaginationControls();
    }

    function renderPaginationControls() {
        paginationContainer.innerHTML = "";
        const totalPages = Math.ceil(processedStudents.length / rowsPerPage);

        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            if (i === currentPage) btn.classList.add("active");
            btn.addEventListener("click", () => {
                currentPage = i;
                renderStudentTable();
            });
            paginationContainer.appendChild(btn);
        }
    }

    function processAndRenderStudents() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : "";
        const course = courseFilter ? courseFilter.value : "All";
        const sortOrder = sortSelect ? sortSelect.value : "name-asc";

        // Filter
        processedStudents = studentsData.filter(student => {
            const matchesSearch = student.name.toLowerCase().includes(query) || student.id.toLowerCase().includes(query);
            const matchesCourse = course === "All" || student.course === course;
            return matchesSearch && matchesCourse;
        });

        // Sort
        processedStudents.sort((a, b) => {
            if (sortOrder === "name-asc") return a.name.localeCompare(b.name);
            if (sortOrder === "name-desc") return b.name.localeCompare(a.name);
            if (sortOrder === "id-asc") return a.id.localeCompare(b.id);
            return 0;
        });

        currentPage = 1;
        renderStudentTable();
    }

    fetchJSONData("students.json").then(data => {
        studentsData = data;
        if (studentLoading) studentLoading.style.display = "none";
        processAndRenderStudents();

        if (searchInput) searchInput.addEventListener("input", processAndRenderStudents);
        if (courseFilter) courseFilter.addEventListener("change", processAndRenderStudents);
        if (sortSelect) sortSelect.addEventListener("change", processAndRenderStudents);
    });
}
//  Dynamic FAQ Accordion & Category Filter (About us.html)

const faqContainer = document.getElementById("faqContainer");
if (faqContainer) {
    const faqLoading = document.getElementById("faqLoading");
    const faqSearch = document.getElementById("faqSearch");
    const faqCategoryFilter = document.getElementById("faqCategoryFilter");

    let faqsData = [];

    function renderFAQs(items) {
        faqContainer.innerHTML = "";

        if (items.length === 0) {
            faqContainer.innerHTML = `<p style="text-align:center;">No FAQs found matching your criteria.</p>`;
            return;
        }

        items.forEach(faq => {
            const itemDiv = document.createElement("div");
            itemDiv.className = "faq-item";
            itemDiv.innerHTML = `
                <button class="faq-question">
                    <span>${faq.question}</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <p>${faq.answer}</p>
                </div>
            `;

            const btn = itemDiv.querySelector(".faq-question");
            const answer = itemDiv.querySelector(".faq-answer");
            const icon = itemDiv.querySelector(".faq-icon");

            btn.addEventListener("click", () => {
                answer.classList.toggle("show");
                icon.innerHTML = answer.classList.contains("show") ? "−" : "+";
            });

            faqContainer.appendChild(itemDiv);
        });
    }

    function filterFAQs() {
        const query = faqSearch ? faqSearch.value.toLowerCase().trim() : "";
        const category = faqCategoryFilter ? faqCategoryFilter.value : "All";

        const filtered = faqsData.filter(faq => {
            const matchesQuery = faq.question.toLowerCase().includes(query) || faq.answer.toLowerCase().includes(query);
            const matchesCategory = category === "All" || faq.category === category;
            return matchesQuery && matchesCategory;
        });

        renderFAQs(filtered);
    }

    fetchJSONData("faqs.json").then(data => {
        faqsData = data;
        if (faqLoading) faqLoading.style.display = "none";
        renderFAQs(faqsData);

        if (faqSearch) faqSearch.addEventListener("input", filterFAQs);
        if (faqCategoryFilter) faqCategoryFilter.addEventListener("change", filterFAQs);
    });
}

// SETTINGS / STUDENT DIRECTORY SWITCH


let settingsBtn = document.getElementById("settingsBtn");
let directoryBtn = document.getElementById("directoryBtn");

let settingsSection = document.getElementById("settingsSection");
let directorySection = document.getElementById("directorySection");


if (settingsBtn && directoryBtn && settingsSection && directorySection) {

    // Settings button
    settingsBtn.addEventListener("click", function() {

        settingsSection.classList.remove("hidden");
        directorySection.classList.add("hidden");

        settingsBtn.classList.add("active");
        directoryBtn.classList.remove("active");

    });


    // Student Directory button
    directoryBtn.addEventListener("click", function() {

        settingsSection.classList.add("hidden");
        directorySection.classList.remove("hidden");

        directoryBtn.classList.add("active");
        settingsBtn.classList.remove("active");

    });

}





