



// function showDateTime()
// {
//     let now = new Date();

//     document.getElementById("date").innerHTML =
//         now.toLocaleDateString();

//     document.getElementById("time").innerHTML =
//         now.toLocaleTimeString();
// }

// setInterval(showDateTime, 1000);

// showDateTime();

// let loginForm = document.getElementById("loginForm");

// if (loginForm)
// {
//     loginForm.addEventListener("submit", function(event)
//     {
//         event.preventDefault();

//         let email = document.getElementById("email").value;
//         let password = document.getElementById("password").value;

//         if (email == "" || password == "")
//         {
//             document.getElementById("loginMessage").innerHTML =
//                 "Please fill all fields.";
//         }
//         else if (password.length < 6)
//         {
//             document.getElementById("loginMessage").innerHTML =
//                 "Password must contain at least 6 characters.";
//         }
//         else
//         {
//             document.getElementById("loginMessage").innerHTML =
//                 "Login successful!";
//         }
//     });
// }   

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
    let email=document.getElementById("email");
let crpassword=document.getElementById("createpassword");
let copassword=document.getElementById("confirmpassword");


    signupform.addEventListener("submit", function(event)
    {
        event.preventDefault();

        if(email.value=="")
        {
            message.innerHTML= "Please enter the valid email ID";
            message.style.color="Blue";
            return;
        }

        if(crpassword.value=="")
        {
            message.innerHTML="Please enter the Password";
            return;
        }

        if(copassword.value=="")
        {
            message.innerHTML="Please confirm the Password";
            return;
        }

        if(crpassword.value!=copassword.value)
        {
            message.innerHTML="Passwords do not match";
            return;
        }
        message.innerHTML="Account Created Succesfully!";    
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


/* =========================================
   Light / Dark Theme Switcher
   ========================================= */
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


/* =========================================
   Hamburger Menu
   ========================================= */
let hamburger = document.getElementById("hamburger");
let navMenu = document.getElementById("navMenu");

if (hamburger && navMenu)
{
    hamburger.addEventListener("click", function()
    {
        navMenu.classList.toggle("active");
    });
}


/* =========================================
   Notification Banner
   ========================================= */
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


/* =========================================
   Collapsible FAQ
   ========================================= */
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


/* =========================================
   Content / Image Slider
   ========================================= */
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





