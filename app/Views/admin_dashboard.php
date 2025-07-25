<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <section id="analytics-report">
            <h2>Analytics Report</h2>
            <div id="report-content">
                <!-- Report data will be dynamically loaded here -->
            </div>
        </section>
    </main>
    <script>
        async function loadAnalyticsReport() {
            const response = await fetch('/admin/analytics/report');
            const report = await response.json();

            const reportContent = document.getElementById('report-content');
            reportContent.innerHTML = '<pre>' + JSON.stringify(report, null, 2) + '</pre>';
        }

        document.addEventListener('DOMContentLoaded', loadAnalyticsReport);
    </script>
</body>
</html>
