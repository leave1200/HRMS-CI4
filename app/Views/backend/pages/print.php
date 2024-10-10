<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Bio Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .profile-picture {
            text-align: center;
        }
        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 10%;
            border: 1px solid #ddd;
        }
        .details {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .details dt {
            font-weight: bold;
        }
        .details dd {
            margin: 0 0 10px 0;
        }
        .section-title {
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-button button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Bio Data</h1>
        </div>
            <div class="profile-picture">
            <img src="<?= htmlspecialchars($employee['picture_url']) ?>" alt="Profile Picture">
         </div>
        <div class="details">
            <div class="section-title">Personal Information</div>
            <dl>
                <dt>First Name:</dt>
                <dd><?= htmlspecialchars($employee['firstname']) ?></dd>
                <dt>Last Name:</dt>
                <dd><?= htmlspecialchars($employee['lastname']) ?></dd>
                <dt>Email:</dt>
                <dd><?= htmlspecialchars($employee['email']) ?></dd>
                <dt>Phone:</dt>
                <dd><?= htmlspecialchars($employee['phone']) ?></dd>
                <dt>Address:</dt>
                <dd><?= htmlspecialchars($employee['address']) ?></dd>
                <dt>Date of Birth:</dt>
                <dd><?= htmlspecialchars($employee['dob']) ?></dd>
            </dl>
        </div>
        <div class="details">
            <div class="section-title">Educational Background</div>
            <dl>
                <dt>Primary School:</dt>
                <dd><?= htmlspecialchars($employee['p_school']) ?></dd>
                <dt>Secondary School:</dt>
                <dd><?= htmlspecialchars($employee['s_school']) ?></dd>
                <dt>Tertiary School:</dt>
                <dd><?= htmlspecialchars($employee['t_school']) ?></dd>
            </dl>
        </div>
        <!-- <div class="details">
            <div class="section-title">Interview Information</div>
            <dl>
                <dt>Interview For:</dt>
                <dd><?= htmlspecialchars($employee['interview_for']) ?></dd>
                <dt>Interview Type:</dt>
                <dd><?= htmlspecialchars($employee['interview_type']) ?></dd>
                <dt>Interview Date:</dt>
                <dd><?= htmlspecialchars($employee['interview_date']) ?></dd>
                <dt>Interview Time:</dt>
                <dd><?= htmlspecialchars($employee['interview_time']) ?></dd>
            </dl>
        </div> -->
        <div class="details">
            <div class="section-title">Remarks</div>
            <dl>
                <dt>Behaviour:</dt>
                <dd><?= htmlspecialchars($employee['behaviour']) ?></dd>
                <dt>Result:</dt>
                <dd><?= htmlspecialchars($employee['result']) ?></dd>
                <dt>Comments:</dt>
                <dd><?= htmlspecialchars($employee['comment']) ?></dd>
            </dl>
        </div>
        <div class="print-button">
            <button onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>
