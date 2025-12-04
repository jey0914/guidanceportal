<?php
session_start();
include("db.php"); // Make sure $conn is defined here

// Fetch appointments with enhanced data
$events = [];
$stats = ['total' => 0, 'pending' => 0, 'confirmed' => 0, 'cancelled' => 0, 'completed' => 0];

$result = $con->query("SELECT name, date, time, status FROM appointments ORDER BY date, time");
while ($row = $result->fetch_assoc()) {
    $stats['total']++;
    
    // Enhanced color coding and status handling
    $color = '#10b981'; // green = confirmed
    $icon = '✅';
    $status = strtolower(trim($row['status']));
    
    switch($status) {
        case 'pending':
            $color = '#f59e0b'; // orange
            $icon = '⏳';
            $stats['pending']++;
            break;
        case 'confirmed':
        case 'available':
            $color = '#10b981'; // green
            $icon = '✅';
            $stats['confirmed']++;
            break;
        case 'cancelled':
        case 'full':
            $color = '#ef4444'; // red
            $icon = '❌';
            $stats['cancelled']++;
            break;
        case 'completed':
            $color = '#3b82f6'; // blue
            $icon = '✨';
            $stats['completed']++;
            break;
        default:
            $stats['confirmed']++;
    }

    $events[] = [
        'id' => uniqid(),
        'title' => $icon . ' ' . $row['name'],
        'start' => $row['date'] . 'T' . $row['time'],
        'color' => $color,
        'extendedProps' => [
            'clientName' => $row['name'],
            'status' => ucfirst($row['status']),
            'time' => date('g:i A', strtotime($row['time'])),
            'date' => date('F j, Y', strtotime($row['date'])),
            'rawStatus' => $status
        ]
    ];
}
$events_json = json_encode($events);
$stats_json = json_encode($stats);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Consultations Calendar - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .calendar-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .calendar-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .calendar-day {
            min-height: 120px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .calendar-day:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        .calendar-day.other-month {
            background: #f9fafb;
            color: #9ca3af;
        }
        
        .calendar-day.today {
            background: rgba(59, 130, 246, 0.1);
            border: 2px solid #3b82f6;
        }
        
        .consultation-event {
            border-radius: 6px;
            padding: 4px 8px;
            margin: 2px 0;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .consultation-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .event-ongoing {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .event-upcoming {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .event-available {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        
        .event-completed {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        
        .view-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.2s ease;
        }
        
        .view-toggle.active {
            background: white;
            color: #3b82f6;
        }
        
        .view-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .time-indicator {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 0 2px 2px 0;
        }
        
        .day-status-indicator {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
            z-index: 10;
        }
        
        .day-full {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .day-available {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .day-partial {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }
        
        .day-empty {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            opacity: 0.6;
        }
        
        .filter-chip {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .filter-chip.active {
            background: white;
            color: #3b82f6;
        }
        
        .filter-chip:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="gradient-bg min-h-full">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-content-between align-items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="floating">
                        <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-lg relative">
                            <i class="fas fa-calendar-alt text-xl text-blue-600"></i>
                            <div class="notification-badge" id="upcomingCount">3</div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">My Consultations</h1>
                        <p class="text-purple-100 text-sm">View and manage your scheduled appointments</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-white text-center">
                        <div class="text-lg font-bold" id="nextAppointment">Today 2:00 PM</div>
                        <div class="text-xs">Next Appointment</div>
                    </div>
                    <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-3xl font-bold text-gray-900" id="totalCount"><?php echo $stats['total']; ?></p>
                    </div>
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-orange-600" id="pendingCount"><?php echo $stats['pending']; ?></p>
                    </div>
                    <div class="h-12 w-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Confirmed</p>
                        <p class="text-3xl font-bold text-green-600" id="confirmedCount"><?php echo $stats['confirmed']; ?></p>
                    </div>
                    <div class="h-12 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Cancelled</p>
                        <p class="text-3xl font-bold text-red-600" id="cancelledCount"><?php echo $stats['cancelled']; ?></p>
                    </div>
                    <div class="h-12 w-12 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-3xl font-bold text-blue-600" id="completedCount"><?php echo $stats['completed']; ?></p>
                    </div>
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Controls -->
        <div class="calendar-container rounded-2xl shadow-xl mb-8 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                <!-- Calendar Navigation -->
                <div class="flex items-center space-x-4">
                    <button onclick="previousMonth()" class="p-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-all duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800" id="currentMonth">December 2024</h2>
                    <button onclick="nextMonth()" class="p-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-all duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button onclick="goToToday()" class="px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition-all duration-200">
                        Today
                    </button>
                </div>

                <!-- View Toggle -->
                <div class="flex space-x-2">
                    <button onclick="setView('month')" class="view-toggle active px-4 py-2 rounded-lg" id="monthView">
                        <i class="fas fa-calendar mr-2"></i>Month
                    </button>
                    <button onclick="setView('week')" class="view-toggle px-4 py-2 rounded-lg" id="weekView">
                        <i class="fas fa-calendar-week mr-2"></i>Week
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="text-gray-600 font-medium mr-2">Filter by type:</span>
                <div class="filter-chip active" onclick="filterConsultations('all')" data-filter="all">
                    All Consultations
                </div>
                <div class="filter-chip" onclick="filterConsultations('counseling')" data-filter="counseling">
                    Counseling
                </div>
                <div class="filter-chip" onclick="filterConsultations('academic')" data-filter="academic">
                    Academic Support
                </div>
                <div class="filter-chip" onclick="filterConsultations('wellness')" data-filter="wellness">
                    Wellness Check
                </div>
                <div class="filter-chip" onclick="filterConsultations('group')" data-filter="group">
                    Group Session
                </div>
            </div>

            <!-- Legend -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Appointment Status</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #f59e0b;"></div>
                        <span class="text-sm text-gray-700">⏳ Pending</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #10b981;"></div>
                        <span class="text-sm text-gray-700">✅ Confirmed</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ef4444;"></div>
                        <span class="text-sm text-gray-700">❌ Cancelled</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #3b82f6;"></div>
                        <span class="text-sm text-gray-700">✨ Completed</span>
                    </div>
                </div>
                
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Daily Availability</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="legend-item">
                        <div class="w-4 h-4 rounded-full bg-gradient-to-r from-red-500 to-red-600"></div>
                        <span class="text-sm text-gray-700">🔴 Fully Booked</span>
                    </div>
                    <div class="legend-item">
                        <div class="w-4 h-4 rounded-full bg-gradient-to-r from-yellow-500 to-orange-500"></div>
                        <span class="text-sm text-gray-700">🟡 Partially Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="w-4 h-4 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>
                        <span class="text-sm text-gray-700">🟢 Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="w-4 h-4 rounded-full bg-gradient-to-r from-gray-500 to-gray-600 opacity-60"></div>
                        <span class="text-sm text-gray-700">⚫ No Slots</span>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="calendar-header rounded-t-lg">
                <div class="grid grid-cols-7 text-center text-white font-semibold py-3">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>
            </div>

            <div class="grid grid-cols-7" id="calendarGrid">
                <!-- Calendar days will be populated here -->
            </div>
        </div>

        <!-- Upcoming Consultations -->
        <div class="calendar-container rounded-2xl shadow-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                Upcoming Consultations
            </h3>
            <div class="space-y-4" id="upcomingList">
                <!-- Upcoming consultations will be populated here -->
            </div>
        </div>
    </main>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div id="eventModalContent">
                <!-- Event details will be populated here -->
            </div>
            <div class="flex space-x-4 mt-6">
                <button onclick="closeEventModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-all duration-200">
                    Close
                </button>
                <button onclick="joinConsultation()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-all duration-200" id="joinButton" style="display: none;">
                    Join Now
                </button>
            </div>
        </div>
    </div>

    <script>
        // Load consultation data from PHP
        const consultations = <?php echo $events_json; ?>;
        const stats = <?php echo $stats_json; ?>;
        
        // Convert PHP events to consultation format
        const formattedConsultations = consultations.map(event => {
            const startDate = new Date(event.start);
            return {
                id: event.id,
                title: event.extendedProps.clientName,
                type: getConsultationType(event.extendedProps.rawStatus),
                date: startDate.toISOString().split('T')[0],
                time: startDate.toTimeString().split(' ')[0].substring(0, 5),
                duration: 60, // Default duration
                staff: "Counselor", // Default staff
                status: event.extendedProps.rawStatus,
                location: "Consultation Room",
                notes: `${event.extendedProps.status} appointment`,
                color: event.color,
                icon: event.title.charAt(0)
            };
        });
        
        // Helper function to determine consultation type
        function getConsultationType(status) {
            switch(status) {
                case 'pending': return 'counseling';
                case 'confirmed': return 'academic';
                case 'cancelled': return 'wellness';
                case 'completed': return 'group';
                default: return 'counseling';
            }
        }

        let currentDate = new Date();
        let currentView = 'month';
        let currentFilter = 'all';

        // Initialize calendar
        function initializeCalendar() {
            updateCalendarHeader();
            renderCalendar();
            renderUpcomingConsultations();
            updateNotificationCount();
        }

        // Update calendar header
        function updateCalendarHeader() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonth').textContent = 
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        // Render calendar
        function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            const today = new Date();
            
            for (let i = 0; i < 42; i++) {
                const cellDate = new Date(startDate);
                cellDate.setDate(startDate.getDate() + i);
                
                const dayDiv = document.createElement('div');
                dayDiv.className = 'calendar-day p-2';
                
                // Add classes for styling
                if (cellDate.getMonth() !== currentDate.getMonth()) {
                    dayDiv.classList.add('other-month');
                }
                
                if (cellDate.toDateString() === today.toDateString()) {
                    dayDiv.classList.add('today');
                }

                // Day number
                const dayNumber = document.createElement('div');
                dayNumber.className = 'font-semibold text-sm mb-1';
                dayNumber.textContent = cellDate.getDate();
                dayDiv.appendChild(dayNumber);

                // Add consultations for this day
                const dayConsultations = formattedConsultations.filter(consultation => {
                    const consultationDate = new Date(consultation.date);
                    return consultationDate.toDateString() === cellDate.toDateString() &&
                           (currentFilter === 'all' || consultation.type === currentFilter);
                });

                // Calculate day availability status
                const dayStatus = calculateDayStatus(dayConsultations, cellDate);
                if (dayStatus.show) {
                    const statusIndicator = document.createElement('div');
                    statusIndicator.className = `day-status-indicator ${dayStatus.class}`;
                    statusIndicator.innerHTML = dayStatus.icon;
                    statusIndicator.title = dayStatus.tooltip;
                    dayDiv.appendChild(statusIndicator);
                }

                dayConsultations.forEach(consultation => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = `consultation-event`;
                    eventDiv.style.background = consultation.color;
                    eventDiv.style.color = 'white';
                    eventDiv.innerHTML = `
                        <div class="text-xs font-medium">${consultation.time}</div>
                        <div class="text-xs truncate">${consultation.icon} ${consultation.title}</div>
                        <div class="text-xs opacity-75">${consultation.status}</div>
                    `;
                    eventDiv.onclick = () => showEventDetail(consultation);
                    dayDiv.appendChild(eventDiv);
                });

                grid.appendChild(dayDiv);
            }
        }

        // Calculate day availability status
        function calculateDayStatus(dayConsultations, date) {
            // Skip past dates and other month dates
            const today = new Date();
            const isToday = date.toDateString() === today.toDateString();
            const isPast = date < today && !isToday;
            const isOtherMonth = date.getMonth() !== currentDate.getMonth();
            
            if (isPast || isOtherMonth) {
                return { show: false };
            }

            // Define maximum slots per day (you can adjust this)
            const maxSlotsPerDay = 8;
            
            // Count different types of appointments
            const totalAppointments = dayConsultations.length;
            const confirmedAppointments = dayConsultations.filter(c => 
                c.status === 'confirmed' || c.status === 'pending'
            ).length;
            const cancelledAppointments = dayConsultations.filter(c => 
                c.status === 'cancelled'
            ).length;
            
            // Calculate availability
            const availableSlots = maxSlotsPerDay - confirmedAppointments;
            const occupancyRate = confirmedAppointments / maxSlotsPerDay;
            
            let status = {
                show: true,
                class: '',
                icon: '',
                tooltip: ''
            };
            
            if (confirmedAppointments >= maxSlotsPerDay) {
                // Fully booked
                status.class = 'day-full';
                status.icon = '🔴';
                status.tooltip = `Fully Booked (${confirmedAppointments}/${maxSlotsPerDay} slots)`;
            } else if (confirmedAppointments > 0) {
                if (occupancyRate >= 0.7) {
                    // Mostly booked
                    status.class = 'day-partial';
                    status.icon = '🟡';
                    status.tooltip = `${availableSlots} slots available (${confirmedAppointments}/${maxSlotsPerDay} booked)`;
                } else {
                    // Some availability
                    status.class = 'day-available';
                    status.icon = '🟢';
                    status.tooltip = `${availableSlots} slots available (${confirmedAppointments}/${maxSlotsPerDay} booked)`;
                }
            } else {
                // Fully available
                status.class = 'day-available';
                status.icon = '🟢';
                status.tooltip = `All ${maxSlotsPerDay} slots available`;
            }
            
            return status;
        }

        // Get status color
        function getStatusColor(status) {
            switch(status) {
                case 'ongoing': return 'red';
                case 'upcoming': return 'green';
                case 'available': return 'yellow';
                case 'completed': return 'gray';
                default: return 'blue';
            }
        }

        // Show event detail modal
        function showEventDetail(consultation) {
            const modal = document.getElementById('eventModal');
            const content = document.getElementById('eventModalContent');
            const joinButton = document.getElementById('joinButton');
            
            const statusIcons = {
                ongoing: 'fas fa-circle text-red-500',
                upcoming: 'fas fa-clock text-green-500',
                available: 'fas fa-calendar-plus text-yellow-500',
                completed: 'fas fa-check-circle text-gray-500'
            };

            const statusLabels = {
                ongoing: 'Ongoing',
                upcoming: 'Upcoming',
                available: 'Available',
                completed: 'Completed'
            };

            content.innerHTML = `
                <div class="text-center mb-6">
                    <div class="h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: ${consultation.color}">
                        <span class="text-2xl text-white">${consultation.icon}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">${consultation.title}</h3>
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background: ${consultation.color}"></div>
                        <span class="text-sm font-medium text-gray-600 capitalize">${consultation.status}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <span class="text-gray-700">${new Date(consultation.date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-clock text-gray-400"></i>
                        <span class="text-gray-700">${consultation.time} (${consultation.duration} minutes)</span>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-user-md text-gray-400"></i>
                        <span class="text-gray-700">${consultation.staff}</span>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        <span class="text-gray-700">${consultation.location}</span>
                    </div>
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-sticky-note text-gray-400 mt-1"></i>
                        <span class="text-gray-700">${consultation.notes}</span>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        <span class="text-gray-700">Appointment Type: ${consultation.type.charAt(0).toUpperCase() + consultation.type.slice(1)}</span>
                    </div>
                </div>
            `;

            // Show join button for ongoing consultations
            if (consultation.status === 'ongoing') {
                joinButton.style.display = 'block';
                joinButton.setAttribute('data-consultation-id', consultation.id);
            } else {
                joinButton.style.display = 'none';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close event modal
        function closeEventModal() {
            document.getElementById('eventModal').classList.add('hidden');
            document.getElementById('eventModal').classList.remove('flex');
        }

        // Join consultation
        function joinConsultation() {
            const consultationId = document.getElementById('joinButton').getAttribute('data-consultation-id');
            // Simulate joining consultation
            alert(`Joining consultation ${consultationId}...`);
            closeEventModal();
        }

        // Render upcoming consultations
        function renderUpcomingConsultations() {
            const container = document.getElementById('upcomingList');
            const upcoming = formattedConsultations
                .filter(c => c.status === 'pending' || c.status === 'confirmed')
                .sort((a, b) => new Date(a.date + ' ' + a.time) - new Date(b.date + ' ' + b.time))
                .slice(0, 5);

            container.innerHTML = '';

            if (upcoming.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-check text-4xl mb-4"></i>
                        <p>No upcoming consultations</p>
                    </div>
                `;
                return;
            }

            upcoming.forEach(consultation => {
                const div = document.createElement('div');
                div.className = `bg-white rounded-lg p-4 border-l-4 border-${getStatusColor(consultation.status)}-500 shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer`;
                div.onclick = () => showEventDetail(consultation);
                
                const consultationDate = new Date(consultation.date + ' ' + consultation.time);
                const timeUntil = getTimeUntil(consultationDate);
                
                div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-1">${consultation.title}</h4>
                            <p class="text-sm text-gray-600 mb-2">${consultation.staff}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span><i class="fas fa-calendar mr-1"></i>${new Date(consultation.date).toLocaleDateString()}</span>
                                <span><i class="fas fa-clock mr-1"></i>${consultation.time}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>${consultation.location}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-${getStatusColor(consultation.status)}-600 mb-1">
                                ${consultation.status === 'ongoing' ? 'LIVE NOW' : timeUntil}
                            </div>
                            ${consultation.status === 'ongoing' ? 
                                '<button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium">Join</button>' : 
                                ''
                            }
                        </div>
                    </div>
                `;
                
                container.appendChild(div);
            });
        }

        // Get time until consultation
        function getTimeUntil(consultationDate) {
            const now = new Date();
            const diff = consultationDate - now;
            
            if (diff < 0) return 'Past';
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            if (days > 0) return `${days}d ${hours}h`;
            if (hours > 0) return `${hours}h ${minutes}m`;
            return `${minutes}m`;
        }

        // Update notification count
        function updateNotificationCount() {
            const upcoming = formattedConsultations.filter(c => c.status === 'pending' || c.status === 'confirmed').length;
            document.getElementById('upcomingCount').textContent = upcoming;
        }

        // Navigation functions
        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendarHeader();
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendarHeader();
            renderCalendar();
        }

        function goToToday() {
            currentDate = new Date();
            updateCalendarHeader();
            renderCalendar();
        }

        // View toggle
        function setView(view) {
            currentView = view;
            
            // Update button states
            document.querySelectorAll('.view-toggle').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(view + 'View').classList.add('active');
            
            // For now, both views show the same calendar
            // In a full implementation, week view would show a different layout
            renderCalendar();
        }

        // Filter consultations
        function filterConsultations(type) {
            currentFilter = type;
            
            // Update filter chips
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.classList.remove('active');
            });
            document.querySelector(`[data-filter="${type}"]`).classList.add('active');
            
            renderCalendar();
        }

        // Initialize the calendar when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();
            
            // Close modal when clicking outside
            document.getElementById('eventModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEventModal();
                }
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'992fd762a1c20dcd',t:'MTc2MTIwNzkwMC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
