<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Revenue</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman Anda -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 8%;
            background-color: #fff;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 77px;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: white;
            color: #FF4B3A;
            border: 2px solid #FF4B3A;
        }

        .btn-primary img {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .page-title {
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            flex: 1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .stat-title {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-subtitle {
            font-size: 12px;
            color: #999;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stat-change {
            font-size: 12px;
            display: flex;
            align-items: center;
        }
        
        .up {
            color: #4CAF50;
        }
        
        .chart {
            height: 80px;
            width: 100%;
        }
        
        .reports-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .reports-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .reports-title {
            font-size: 16px;
            font-weight: bold;
        }
        
        .reports-subtitle {
            font-size: 12px;
            color: #999;
        }
        
        .reports-stats {
            display: flex;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .report-stat {
            flex: 1;
            text-align: center;
        }
        
        .report-stat-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-stat-label {
            font-size: 12px;
            color: #999;
        }
        
        .report-stat:first-child {
            border-bottom: 2px solid #f05545;
        }
        
        .week-chart {
            height: 150px;
            width: 100%;
        }
        
        .transactions-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .transactions-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .transactions-title {
            font-size: 16px;
            font-weight: bold;
        }
        
        .view-all {
            color: #f05545;
            text-decoration: none;
            font-size: 14px;
        }
        
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .transactions-table th {
            text-align: left;
            padding: 10px;
            color: #999;
            font-size: 12px;
            font-weight: normal;
            border-bottom: 1px solid #eee;
        }
        
        .transactions-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .transaction-id {
            color: #f05545;
        }
        
        .view-detail {
            color: #f05545;
            text-decoration: none;
        }
        
        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            margin-top: 30px;
        }

        .chart-container {
            overflow: hidden;
        }

        .revenue-line {
            fill: none;
            stroke: #f05545;
            stroke-width: 2;
        }

        .revenue-area {
            fill: rgba(240, 85, 69, 0.1);
        }

        .orders-line {
            fill: none;
            stroke: #f05545;
            stroke-width: 2;
        }

        .profit-line {
            fill: none;
            stroke: #f05545;
            stroke-width: 2;
        }

        .weekly-revenue-line {
            fill: none;
            stroke: #f05545;
            stroke-width: 2;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="/Assets/logo hd.png" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>

        <div class="auth-buttons">
            <a href="{{route('landing')}}" class="btn btn-primary">
                <img src="Assets/logout.png" alt="Logout Icon">
                Logout
            </a>
        </div>
    </header>
    
    <div class="container">
        <div class="container">
            <button class="back-btn" onclick="window.location.href='{{route('admin')}}'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </button>
            
            <h1 class="page-title">Pendapatan</h1>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-title">Total Revenue</div>
                <div class="stat-subtitle">Today</div>
                <div class="stat-value">Rp590.000</div>
                <div class="stat-change up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 15L12 9L6 15" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    5% vs yesterday
                </div>
                <div class="chart">
                    <svg width="100%" height="80" viewBox="0 0 300 80" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="revenueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:rgba(240, 85, 69, 0.2)" />
                                <stop offset="100%" style="stop-color:rgba(240, 85, 69, 0)" />
                            </linearGradient>
                        </defs>
                        <!-- Area below the line -->
                        <path class="revenue-area" d="M0,60 C20,55 40,50 60,45 C80,40 100,35 120,37 C140,39 160,45 180,35 C200,25 220,20 240,15 C260,10 280,5 300,5 L300,80 L0,80 Z" />
                        <!-- Line for revenue -->
                        <path class="revenue-line" d="M0,60 C20,55 40,50 60,45 C80,40 100,35 120,37 C140,39 160,45 180,35 C200,25 220,20 240,15 C260,10 280,5 300,5" />
                    </svg>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-title">Total Orders</div>
                <div class="stat-subtitle">Today</div>
                <div class="stat-value">25</div>
                <div class="stat-change up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 15L12 9L6 15" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    0% vs yesterday
                </div>
                <div class="chart">
                    <svg width="100%" height="80" viewBox="0 0 300 80" preserveAspectRatio="none">
                        <path class="orders-line" d="M0,60 C20,65 40,70 60,65 C80,60 100,30 120,25 C140,20 160,30 180,30 C200,30 220,20 240,15 C260,12 280,25 300,20" />
                    </svg>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-title">Total Profit</div>
                <div class="stat-subtitle">Today</div>
                <div class="stat-value">Rp238.000</div>
                <div class="stat-change up">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 15L12 9L6 15" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    12% vs yesterday
                </div>
                <div class="chart">
                    <svg width="100%" height="80" viewBox="0 0 300 80" preserveAspectRatio="none">
                        <path class="profit-line" d="M0,60 C20,58 40,56 60,60 C80,64 100,70 120,65 C140,60 160,55 180,60 C200,65 220,30 240,30 C260,30 280,25 300,15" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="reports-card">
            <div class="reports-header">
                <div>
                    <div class="reports-title">Reports</div>
                    <div class="reports-subtitle">Last 7 days</div>
                </div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" fill="black"/>
                    <path d="M19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12C18 12.5523 18.4477 13 19 13Z" fill="black"/>
                    <path d="M5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13Z" fill="black"/>
                </svg>
            </div>
            
            <div class="reports-stats">
                <div class="report-stat">
                    <div class="report-stat-value">240</div>
                    <div class="report-stat-label">Customers</div>
                </div>
                
                <div class="report-stat">
                    <div class="report-stat-value">250</div>
                    <div class="report-stat-label">Total Orders</div>
                </div>
                
                <div class="report-stat">
                    <div class="report-stat-value">Rp598.000</div>
                    <div class="report-stat-label">Total Profit</div>
                </div>
                
                <div class="report-stat">
                    <div class="report-stat-value">Rp1.290.000</div>
                    <div class="report-stat-label">Total Revenue</div>
                </div>
            </div>
            
            <div class="week-chart">
                <svg width="100%" height="150" viewBox="0 0 700 150" preserveAspectRatio="none">
                    <!-- Grid lines -->
                    <line x1="0" y1="30" x2="700" y2="30" stroke="#f0f0f0" stroke-width="1" />
                    <line x1="0" y1="60" x2="700" y2="60" stroke="#f0f0f0" stroke-width="1" />
                    <line x1="0" y1="90" x2="700" y2="90" stroke="#f0f0f0" stroke-width="1" />
                    <line x1="0" y1="120" x2="700" y2="120" stroke="#f0f0f0" stroke-width="1" />
                    
                    <!-- Days of week -->
                    <text x="50" y="145" font-size="10" text-anchor="middle" fill="#999">Mon</text>
                    <text x="150" y="145" font-size="10" text-anchor="middle" fill="#999">Tue</text>
                    <text x="250" y="145" font-size="10" text-anchor="middle" fill="#999">Wed</text>
                    <text x="350" y="145" font-size="10" text-anchor="middle" fill="#999">Thu</text>
                    <text x="450" y="145" font-size="10" text-anchor="middle" fill="#999">Fri</text>
                    <text x="550" y="145" font-size="10" text-anchor="middle" fill="#999">Sat</text>
                    <text x="650" y="145" font-size="10" text-anchor="middle" fill="#999">Sun</text>
                    
                    <!-- Y-axis labels -->
                    <text x="10" y="125" font-size="8" text-anchor="start" fill="#999">0</text>
                    <text x="10" y="95" font-size="8" text-anchor="start" fill="#999">10</text>
                    <text x="10" y="65" font-size="8" text-anchor="start" fill="#999">20</text>
                    <text x="10" y="35" font-size="8" text-anchor="start" fill="#999">30</text>
                    
                    <!-- Revenue line chart -->
                    <path class="weekly-revenue-line" d="M50,120 L100,90 L150,85 L200,70 L250,50 L300,60 L350,55 L400,30 L450,40 L500,35 L550,40 L600,50 L650,45" />
                </svg>
            </div>
        </div>
        
        <div class="transactions-card">
            <div class="transactions-header">
                <div class="transactions-title">Last Transactions</div>
                <a href="#" class="view-all">View All</a>
            </div>
            
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>TRANSACTION ID</th>
                        <th>ISSUED DATE</th>
                        <th>TOTAL</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                    <tr>
                        <td class="transaction-id">#T5089</td>
                        <td>2025/04/15 09:57:00</td>
                        <td>Rp35.000</td>
                        <td><a href="#" class="view-detail">View Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>
</body>
</html>