<!DOCTYPE html>
<html>
<head>
    <title>Holiday Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
            font-weight: bold;
        }

        select, button {
            padding: 8px;
            font-size: 16px;
            margin-right: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Holiday Calendar</h1>

        <!-- Form for selecting country and year -->
        <form action="{{ route('holidays.fetch') }}" method="GET">
            <label for="country">Country:</label>
            <select name="country" id="country">
		 <option value="IN" {{ request('country') == 'IN' ? 'selected' : '' }}>India</option>
                <option value="US" {{ request('country') == 'US' ? 'selected' : '' }}>United States</option>
                <option value="GB" {{ request('country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                <!-- Add more countries as needed -->
            </select>

            <label for="year">Year:</label>
            <select name="year" id="year">
                @for ($i = 2020; $i <= 2030; $i++)
                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>

            <button type="submit">Fetch Holidays</button>
        </form>

        <!-- Display the holidays if they are available -->
        @if(isset($holidays) && !empty($holidays))
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($holidays as $holiday)
                        <tr>
                            <td>{{ $holiday['name'] ?? 'N/A' }}</td>
                            <td>{{ $holiday['date']['iso'] ?? 'N/A' }}</td>
                            <td>{{ $holiday['primary_type'] ?? 'N/A' }}</td>
                            <td>{{ $holiday['description'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif(isset($holidays))
            <p class="no-results">No holidays found for the selected country and year.</p>
        @endif
    </div>

</body>
</html>

