
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student CRUD - Fetch API</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #eaf2ff;
            color: #222;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        h1 { color: #0b5ed7; }

        input, select, button {
            width: 100%;
            padding: 11px;
            margin: 7px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #0b5ed7;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:disabled {
            opacity: 0.6;
            cursor: wait;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin: 12px 0;
        }

        .success { background: #d1e7dd; }
        .error { background: #f8d7da; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th { background: #0b5ed7; color: white; }

        .actions {
            display: flex;
            gap: 6px;
        }

        .actions button { min-width: 65px; }
        .delete-btn { background: #dc3545; }
        .cancel-btn { background: #555; }

        @media (max-width: 600px) {
            body { padding: 10px; }
            .container { padding: 14px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
<div class="container">
    <h1>Student Management System</h1>
    <p>PHP JSON API and Fetch-Based CRUD Without Page Reload</p>

    <div id="message" role="status" aria-live="polite"></div>

    <h2 id="formTitle">Add Student</h2>

    <form id="studentForm">
        <input type="hidden" id="studentId">

        <div class="form-grid">
            <div>
                <label for="name">Student Name</label>
                <input id="name" maxlength="100" required>
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" maxlength="100" required>
            </div>

            <div>
                <label for="course">Course</label>
                <select id="course" required>
                    <option value="">Select Course</option>
                    <option value="WDF">WDF</option>
                    <option value="C++">C++</option>
                    <option value="DSA">DSA</option>
                </select>
            </div>

            <div>
                <label for="year">Academic Year</label>
                <select id="year" required>
                    <option value="">Select Year</option>
                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>
                    <option value="4">Year 4</option>
                </select>
            </div>
        </div>

        <button id="saveButton" type="submit">Add Student</button>
        <button id="cancelButton" class="cancel-btn"
                type="button" hidden>Cancel Edit</button>
    </form>

    <h2>Student Records</h2>
    <p id="loading" role="status">Loading student records...</p>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="studentTable"></tbody>
        </table>
    </div>
</div>

<script>
const apiURL = "api.php";

const form = document.getElementById("studentForm");
const messageBox = document.getElementById("message");
const tableBody = document.getElementById("studentTable");
const loading = document.getElementById("loading");
const saveButton = document.getElementById("saveButton");
const cancelButton = document.getElementById("cancelButton");

let students = [];

function showMessage(message, success = true) {
    messageBox.textContent = message;
    messageBox.className = "message " +
        (success ? "success" : "error");
}

async function requestAPI(url, options = {}) {
    const response = await fetch(url, options);

    let result;

    try {
        result = await response.json();
    } catch {
        throw new Error("The server returned an invalid response.");
    }

    if (!response.ok || !result.success) {
        throw new Error(result.message || "Request failed.");
    }

    return result;
}

// READ: Load records using GET.
async function loadStudents() {
    loading.hidden = false;
    loading.textContent = "Loading student records...";

    try {
        const result = await requestAPI(apiURL);
        students = result.data || [];
        renderStudents();
        loading.hidden = true;
    } catch (error) {
        loading.textContent = "Could not load student records.";
        showMessage(error.message, false);
    }
}

// Display records safely without inserting user text as HTML.
function renderStudents() {
    tableBody.replaceChildren();

    if (students.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.colSpan = 6;
        cell.textContent = "No students found.";
        row.appendChild(cell);
        tableBody.appendChild(row);
        return;
    }

    students.forEach(student => {
        const row = document.createElement("tr");

        [
            student.id,
            student.name,
            student.email,
            student.course,
            student.year
        ].forEach(value => {
            const cell = document.createElement("td");
            cell.textContent = value;
            row.appendChild(cell);
        });

        const actions = document.createElement("td");
        actions.className = "actions";

        const editButton = document.createElement("button");
        editButton.type = "button";
        editButton.textContent = "Edit";
        editButton.addEventListener("click", () => editStudent(student.id));

        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.textContent = "Delete";
        deleteButton.className = "delete-btn";
        deleteButton.addEventListener(
            "click", () => deleteStudent(student.id)
        );

        actions.append(editButton, deleteButton);
        row.appendChild(actions);
        tableBody.appendChild(row);
    });
}

// CREATE and UPDATE: Submit JSON without reloading.
form.addEventListener("submit", async function(event) {
    event.preventDefault();

    const id = document.getElementById("studentId").value;

    const payload = {
        action: id ? "update" : "add",
        name: document.getElementById("name").value.trim(),
        email: document.getElementById("email").value.trim(),
        course: document.getElementById("course").value,
        year: Number(document.getElementById("year").value)
    };

    if (id) {
        payload.id = Number(id);
    }

    saveButton.disabled = true;

    try {
        const result = await requestAPI(apiURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        showMessage(result.message);
        resetForm();
        await loadStudents();
    } catch (error) {
        showMessage(error.message, false);
    } finally {
        saveButton.disabled = false;
    }
});

// Fill the form with the selected student.
function editStudent(id) {
    const student = students.find(item => item.id === id);

    if (!student) {
        showMessage("Student not found.", false);
        return;
    }

    document.getElementById("studentId").value = student.id;
    document.getElementById("name").value = student.name;
    document.getElementById("email").value = student.email;
    document.getElementById("course").value = student.course;
    document.getElementById("year").value = student.year;

    document.getElementById("formTitle").textContent = "Edit Student";
    saveButton.textContent = "Update Student";
    cancelButton.hidden = false;

    document.getElementById("name").focus();
}

// DELETE: Send a JSON request after confirmation.
async function deleteStudent(id) {
    if (!confirm("Are you sure you want to delete this student?")) {
        return;
    }

    try {
        const result = await requestAPI(apiURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "delete",
                id: id
            })
        });

        showMessage(result.message);
        await loadStudents();
    } catch (error) {
        showMessage(error.message, false);
    }
}

function resetForm() {
    form.reset();
    document.getElementById("studentId").value = "";
    document.getElementById("formTitle").textContent = "Add Student";
    saveButton.textContent = "Add Student";
    cancelButton.hidden = true;
}

cancelButton.addEventListener("click", resetForm);

// Initial asynchronous load.
loadStudents();
</script>
</body>
</html>
