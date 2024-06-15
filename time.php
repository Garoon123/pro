<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
        }

        .header .buttons {
            display: flex;
            gap: 10px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .buttons .active {
            background-color: #4CAF50;
            color: white;
        }

        .calendar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .day {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            min-width: 150px;
            /* Adjust the minimum width as needed */
        }

        .day h3 {
            margin-bottom: 10px;
        }

        .slot {
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .booked {
            background-color: #f0f0f0;
        }

        .available {
            background-color: #e0ffe0;
            border-color: #4CAF50;
        }

        @media (max-width: 600px) {
            .container {
                width: 100%;
                padding: 10px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header .buttons {
                margin-top: 10px;
            }

            .calendar {
                flex-direction: column;
                align-items: center;
            }

            .day {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Schedule</h1>
            <div class="buttons">
                <button>Morning</button>
                <button class="active">Afternoon</button>
            </div>
        </div>
        <div class="calendar">
            <div class="day">
                <h3>Wed 22nd</h3>
                <div class="slot booked">18:55<br>Booked<br>45 min</div>
                <div class="slot booked">19:40<br>Booked<br>45 min</div>
                <div class="slot booked">20:25<br>Booked<br>45 min</div>
                <div class="slot booked">20:25<br>Booked<br>45 min</div>
            </div>
            <div class="day">
                <h3>Thu 23rd</h3>
                <div class="slot booked">18:10<br>Booked<br>45 min</div>
                <div class="slot booked">18:55<br>Booked<br>45 min</div>
                <div class="slot booked">19:40<br>Booked<br>45 min</div>
                <div class="slot booked">20:25<br>Booked<br>45 min</div>
            </div>
        </div>
    </div>
</body>

</html>