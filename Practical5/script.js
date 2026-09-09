  
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







