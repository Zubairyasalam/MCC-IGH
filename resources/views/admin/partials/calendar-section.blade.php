<!-- Filter Control Toolbar -->
<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 1rem;">
        <div>
            <h3 style="margin:0; font-size: 1.2rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-calendar-blank" style="color: var(--primary-color);"></i>
                Detailed Room Reservations Calendar
            </h3>
            <span style="font-size: 0.8rem; color: #64748b; margin-top: 3px; display: block;">
                Currently viewing <strong>{{ $calendarDate->format('F Y') }}</strong> ({{ ucfirst($viewMode) }} View)
            </span>
        </div>

        <!-- View Mode Pills (Monthly / Weekly / Daily) -->
        <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 10px; gap: 4px;">
            <a href="{{ route('admin.dashboard', ['view' => 'monthly', 'month' => $selectedMonth, 'year' => $selectedYear, 'date' => $selectedDateStr]) }}"
               class="ajax-cal-link"
               style="padding: 6px 16px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; transition: all 0.2s; color: {{ $viewMode === 'monthly' ? '#ffffff' : '#475569' }}; background: {{ $viewMode === 'monthly' ? 'var(--primary-color, #850f0f)' : 'transparent' }};">
               <i class="ph-bold ph-calendar-blank"></i> Monthly
            </a>
            <a href="{{ route('admin.dashboard', ['view' => 'weekly', 'month' => $selectedMonth, 'year' => $selectedYear, 'date' => $selectedDateStr]) }}"
               class="ajax-cal-link"
               style="padding: 6px 16px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; transition: all 0.2s; color: {{ $viewMode === 'weekly' ? '#ffffff' : '#475569' }}; background: {{ $viewMode === 'weekly' ? 'var(--primary-color, #850f0f)' : 'transparent' }};">
               <i class="ph-bold ph-calendar-check"></i> Weekly
            </a>
            <a href="{{ route('admin.dashboard', ['view' => 'daily', 'month' => $selectedMonth, 'year' => $selectedYear, 'date' => $selectedDateStr]) }}"
               class="ajax-cal-link"
               style="padding: 6px 16px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; text-decoration: none; transition: all 0.2s; color: {{ $viewMode === 'daily' ? '#ffffff' : '#475569' }}; background: {{ $viewMode === 'daily' ? 'var(--primary-color, #850f0f)' : 'transparent' }};">
               <i class="ph-bold ph-clock"></i> Daily
            </a>
        </div>
    </div>

    <!-- Month, Year, Date Filter Form -->
    <form id="ajaxCalendarForm" method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 0.85rem;">
        <input type="hidden" name="view" value="{{ $viewMode }}">
        
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <!-- Month Dropdown -->
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.78rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.03em;">Month:</label>
                <select name="month" class="ajax-cal-select" style="padding: 6px 14px; font-size: 0.82rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #0f172a; cursor: pointer; outline: none;">
                    @foreach(range(1, 12) as $m)
                        @php $mName = \Carbon\Carbon::create()->month($m)->format('F'); @endphp
                        <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Dropdown -->
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.78rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.03em;">Year:</label>
                <select name="year" class="ajax-cal-select" style="padding: 6px 14px; font-size: 0.82rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #0f172a; cursor: pointer; outline: none;">
                    @foreach(range(\Carbon\Carbon::now()->year - 2, \Carbon\Carbon::now()->year + 3) as $y)
                        <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Specific Date Selector -->
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 0.78rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.03em;">Jump to Date:</label>
                <input type="date" name="date" value="{{ $selectedDateStr }}" class="ajax-cal-select" style="padding: 5px 12px; font-size: 0.82rem; font-weight: 600; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #0f172a; cursor: pointer; outline: none;">
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <!-- Prev / Next Month Navigation -->
            @php
                $prevMonthObj = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonth();
                $nextMonthObj = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->addMonth();
            @endphp
            <a href="{{ route('admin.dashboard', ['view' => $viewMode, 'month' => $prevMonthObj->month, 'year' => $prevMonthObj->year]) }}" 
               class="ajax-cal-link"
               title="Previous Month ({{ $prevMonthObj->format('F Y') }})"
               style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="ph-bold ph-caret-left"></i> Prev Month
            </a>
            
            <a href="{{ route('admin.dashboard', ['view' => $viewMode, 'month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year, 'date' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" 
               class="ajax-cal-link"
               style="padding: 6px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; border: 1.5px solid var(--primary-color); background: rgba(133, 15, 15, 0.06); color: var(--primary-color, #850f0f); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="ph-bold ph-calendar"></i> Today
            </a>

            <a href="{{ route('admin.dashboard', ['view' => $viewMode, 'month' => $nextMonthObj->month, 'year' => $nextMonthObj->year]) }}" 
               class="ajax-cal-link"
               title="Next Month ({{ $nextMonthObj->format('F Y') }})"
               style="padding: 6px 12px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                Next Month <i class="ph-bold ph-caret-right"></i>
            </a>

            <a href="{{ route('admin.college-guest') }}" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.78rem; border-radius: 8px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: var(--primary-color, #850f0f); color: white; margin-left: 6px;">
                <i class="ph-bold ph-plus"></i> New Reservation
            </a>
        </div>
    </form>
</div>

@if($viewMode === 'monthly')
    <!-- Color Legend for Monthly View -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 1rem;">
        <div style="font-size: 0.85rem; font-weight: 800; color: #1e293b;">
            Monthly Calendar Matrix ({{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y') }})
        </div>
        <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 6px 14px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.72rem; font-weight: 700;">
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #166534;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span> All Free
            </span>
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #b45309;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #d97706;"></span> Partial Reserved
            </span>
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #991b1b;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #dc2626;"></span> High Occupancy
            </span>
            <span style="display: inline-flex; align-items: center; gap: 4px; color: #850f0f;">
                <span style="width: 8px; height: 8px; border-radius: 50%; border: 2px solid #850f0f; background: transparent;"></span> Today
            </span>
        </div>
    </div>

    <div class="detailed-calendar-grid">
        <div class="cal-day-header">Sunday</div>
        <div class="cal-day-header">Monday</div>
        <div class="cal-day-header">Tuesday</div>
        <div class="cal-day-header">Wednesday</div>
        <div class="cal-day-header">Thursday</div>
        <div class="cal-day-header">Friday</div>
        <div class="cal-day-header">Saturday</div>

        @php
            $startOfMonthObj = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $daysInMonth = $startOfMonthObj->daysInMonth;
            $dayOfWeek = $startOfMonthObj->dayOfWeek;
            $todayObj = \Carbon\Carbon::now();
        @endphp

        @for($i = 0; $i < $dayOfWeek; $i++)
            <div class="cal-cell empty-cell"></div>
        @endfor

        @for($d = 1; $d <= $daysInMonth; $d++)
            @php
                $dayBookingsList = $calendarBookings[$d] ?? [];
                $count = count($dayBookingsList);
                $dateObj = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, $d);
                $dateStr = $dateObj->format('F d, Y');
                $dateIso = $dateObj->format('Y-m-d');
                $isToday = ($dateObj->isSameDay($todayObj));

                // Category Breakdown Counts for this Day
                $stdCount = 0;
                $dlxCount = 0;
                $hallCount = 0;
                foreach ($dayBookingsList as $bItem) {
                    $rNameLower = strtolower($bItem['room_name']);
                    if (str_contains($rNameLower, 'standard')) {
                        $stdCount++;
                    } elseif (str_contains($rNameLower, 'deluxe') || str_contains($rNameLower, 'advance') || str_contains($rNameLower, 'executive')) {
                        $dlxCount++;
                    } else {
                        $hallCount++;
                    }
                }

                $freeRooms = max(0, 26 - $count);

                $cellStatusClass = 'cell-available';
                if ($count >= 5) {
                    $cellStatusClass = 'cell-heavy';
                } elseif ($count >= 1) {
                    $cellStatusClass = 'cell-partial';
                }
            @endphp

            <div class="cal-cell {{ $cellStatusClass }} {{ $isToday ? 'cell-today' : '' }}"
                 onclick="openCalendarDayModal({{ $d }}, '{{ $dateStr }}', '{{ $dateIso }}')"
                 style="cursor: pointer;">
                <div class="cell-top-bar">
                    <span class="cell-date-num">{{ $d }}</span>
                    @if($count > 0)
                        <span class="cell-count-badge {{ $count >= 5 ? 'badge-red' : 'badge-amber' }}">{{ $count }} Reserved</span>
                    @else
                        <span class="cell-free-tag"><i class="ph-bold ph-check" style="font-size: 0.65rem;"></i> All Free</span>
                    @endif
                </div>

                <div class="cell-bookings-preview" style="display: flex; flex-direction: column; gap: 3px; margin-top: 4px;">
                    @if($count > 0)
                        @if($stdCount > 0)
                            <div style="font-size: 0.68rem; font-weight: 700; color: #850f0f; background: #fff5f5; border: 1px solid #fecdd3; padding: 2px 6px; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="ph-bold ph-bed"></i> Standard</span>
                                <span style="font-weight: 800;">{{ $stdCount }}</span>
                            </div>
                        @endif
                        @if($dlxCount > 0)
                            <div style="font-size: 0.68rem; font-weight: 700; color: #b45309; background: #fffbeb; border: 1px solid #fef3c7; padding: 2px 6px; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="ph-bold ph-star"></i> Deluxe/Exec</span>
                                <span style="font-weight: 800;">{{ $dlxCount }}</span>
                            </div>
                        @endif
                        @if($hallCount > 0)
                            <div style="font-size: 0.68rem; font-weight: 700; color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; padding: 2px 6px; border-radius: 4px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="ph-bold ph-buildings"></i> Halls</span>
                                <span style="font-weight: 800;">{{ $hallCount }}</span>
                            </div>
                        @endif
                        <div style="font-size: 0.64rem; color: #64748b; font-weight: 700; margin-top: 2px; text-align: center;">
                            {{ $freeRooms }} Free • Click details
                        </div>
                    @else
                        <div class="cell-no-bookings" style="font-size: 0.72rem; color: #166534; font-weight: 700;">
                            <i class="ph-bold ph-check-circle"></i> 26 Available
                        </div>
                        <div style="font-size: 0.62rem; color: #94a3b8; margin-top: 2px; text-align: center;">
                            Click to reserve
                        </div>
                    @endif
                </div>
            </div>
        @endfor
    </div>

@elseif($viewMode === 'weekly')
    <!-- Weekly View Grid -->
    @php
        $startOfWeek = $calendarDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfWeek = $calendarDate->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
        $prevWeekDate = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeekDate = $startOfWeek->copy()->addWeek()->format('Y-m-d');
    @endphp

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; background: #f8fafc; padding: 10px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
        <span style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">
            <i class="ph-bold ph-calendar-check" style="color: var(--primary-color);"></i>
            Weekly Overview: {{ $startOfWeek->format('d M Y') }} &ndash; {{ $endOfWeek->format('d M Y') }}
        </span>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.dashboard', ['view' => 'weekly', 'date' => $prevWeekDate]) }}" class="ajax-cal-link" style="padding: 5px 12px; font-size: 0.78rem; font-weight: 700; border-radius: 6px; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; text-decoration: none;">
                <i class="ph-bold ph-caret-left"></i> Prev Week
            </a>
            <a href="{{ route('admin.dashboard', ['view' => 'weekly', 'date' => $nextWeekDate]) }}" class="ajax-cal-link" style="padding: 5px 12px; font-size: 0.78rem; font-weight: 700; border-radius: 6px; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; text-decoration: none;">
                Next Week <i class="ph-bold ph-caret-right"></i>
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px;">
        @for($w = 0; $w < 7; $w++)
            @php
                $wDate = $startOfWeek->copy()->addDays($w);
                $wDateIso = $wDate->format('Y-m-d');
                $wBookings = $calendarBookingsByDate[$wDateIso] ?? [];
                $wCount = count($wBookings);
                $isTodayW = $wDate->isSameDay(\Carbon\Carbon::now());
            @endphp
            <div style="background: #ffffff; border: 1.5px solid {{ $isTodayW ? 'var(--primary-color, #850f0f)' : '#e2e8f0' }}; border-radius: 12px; padding: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.6rem; border-bottom: 1px solid #f1f5f9; margin-bottom: 0.75rem;">
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: {{ $isTodayW ? 'var(--primary-color, #850f0f)' : '#64748b' }};">
                            {{ $wDate->format('l') }}
                        </div>
                        <div style="font-size: 1rem; font-weight: 800; color: #0f172a;">
                            {{ $wDate->format('M d, Y') }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        @if($wCount > 0)
                            <span style="padding: 3px 8px; font-size: 0.7rem; font-weight: 800; border-radius: 6px; background: #fff5f5; border: 1px solid #fecdd3; color: #850f0f;">
                                {{ $wCount }} Reserved
                            </span>
                        @else
                            <span style="padding: 3px 8px; font-size: 0.7rem; font-weight: 800; border-radius: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                                All Free
                            </span>
                        @endif
                    </div>
                </div>

                @if($wCount > 0)
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($wBookings as $wb)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; font-size: 0.78rem;">
                                <div style="display: flex; justify-content: space-between; font-weight: 800; color: #0f172a; margin-bottom: 2px;">
                                    <span>{{ $wb['name'] }}</span>
                                    <span style="color: var(--primary-color, #850f0f);">#{{ $wb['id'] }}</span>
                                </div>
                                <div style="color: #475569; font-weight: 600; font-size: 0.74rem;">
                                    <i class="ph-bold ph-bed"></i> {{ $wb['room_name'] }}
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px; font-size: 0.7rem; color: #64748b;">
                                    <span><i class="ph-bold ph-clock"></i> {{ $wb['clock_in'] }}</span>
                                    <a href="{{ $wb['details_url'] }}" style="color: var(--primary-color); font-weight: 700;">Details &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 1.5rem 0.5rem; text-align: center; color: #64748b; background: #fafafa; border-radius: 8px;">
                        <i class="ph-bold ph-check-circle" style="font-size: 1.5rem; color: #22c55e; margin-bottom: 4px; display: block;"></i>
                        <span style="font-size: 0.78rem; font-weight: 700; color: #166534; display: block;">26 Rooms Available</span>
                        <a href="{{ route('admin.college-guest') }}?date={{ $wDateIso }}" style="font-size: 0.72rem; color: var(--primary-color); font-weight: 700; text-decoration: underline; margin-top: 4px; display: inline-block;">+ Reserve</a>
                    </div>
                @endif
            </div>
        @endfor
    </div>

@elseif($viewMode === 'daily')
    <!-- Daily Detailed Schedule View -->
    @php
        $dayIso = $calendarDate->format('Y-m-d');
        $dayBookings = $calendarBookingsByDate[$dayIso] ?? [];
        $dReservedCount = count($dayBookings);
        $dAvailableCount = max(0, 26 - $dReservedCount);
        $prevDayIso = $calendarDate->copy()->subDay()->format('Y-m-d');
        $nextDayIso = $calendarDate->copy()->addDay()->format('Y-m-d');
    @endphp

    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
            <div>
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; display: block;">Daily Reservation Details</span>
                <h3 style="margin: 0.2rem 0 0 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">
                    {{ $calendarDate->format('F d, Y') }} ({{ $calendarDate->format('l') }})
                </h3>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="{{ route('admin.dashboard', ['view' => 'daily', 'date' => $prevDayIso]) }}" class="ajax-cal-link" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="ph-bold ph-caret-left"></i> Previous Day
                </a>
                <a href="{{ route('admin.dashboard', ['view' => 'daily', 'date' => $nextDayIso]) }}" class="ajax-cal-link" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 700; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    Next Day <i class="ph-bold ph-caret-right"></i>
                </a>
                <a href="{{ route('admin.college-guest') }}?date={{ $dayIso }}" style="padding: 6px 14px; font-size: 0.8rem; font-weight: 700; background: var(--primary-color, #850f0f); color: white; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="ph-bold ph-plus"></i> Reserve Room
                </a>
            </div>
        </div>

        <!-- Occupancy Summary Ribbon -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 1.5rem; background: #f8fafc; padding: 1rem; border-radius: 10px; border: 1px solid #e2e8f0;">
            <div style="flex: 1; min-width: 150px; background: white; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Total Reserved</span>
                <div style="font-size: 1.25rem; font-weight: 800; color: #850f0f;">{{ $dReservedCount }} / 26 Rooms</div>
            </div>
            <div style="flex: 1; min-width: 150px; background: white; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1;">
                <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Available Rooms</span>
                <div style="font-size: 1.25rem; font-weight: 800; color: #166534;">{{ $dAvailableCount }} Rooms Free</div>
            </div>
        </div>

        <!-- Reservation Cards for the Day -->
        @if($dReservedCount === 0)
            <div style="text-align: center; padding: 2.5rem 1rem; background: #f0fdf4; border-radius: 12px; border: 1px dashed #bbf7d0;">
                <i class="ph-bold ph-check-circle" style="font-size: 2.8rem; color: #22c55e; margin-bottom: 0.5rem; display: block;"></i>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1.1rem; font-weight: 800; color: #166534;">All 26 Rooms Are Available</h4>
                <p style="font-size: 0.85rem; color: #15803d; margin-bottom: 1.2rem;">There are no active room reservations recorded for {{ $calendarDate->format('F d, Y') }}.</p>
                <a href="{{ route('admin.college-guest') }}?date={{ $dayIso }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 0.85rem; font-weight: 700; border-radius: 8px; background: var(--primary-color, #850f0f); color: white; text-decoration: none;">
                    <i class="ph-bold ph-plus-circle"></i> Create Reservation for {{ $calendarDate->format('M d') }}
                </a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach($dayBookings as $db)
                    @php
                        $statusPillClass = $db['payment_status'] === 'Paid' ? 'pill-paid' : ($db['payment_status'] === 'Pending' ? 'pill-pending' : 'pill-failed');
                    @endphp
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; border-bottom: 1px dashed #f1f5f9; padding-bottom: 0.6rem; gap: 10px;">
                            <div>
                                <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary-color, #850f0f); background: rgba(133, 15, 15, 0.08); padding: 2px 8px; border-radius: 6px;">
                                    Booking #{{ $db['id'] }}
                                </span>
                                <h4 style="margin: 0.35rem 0 0 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">{{ $db['name'] }}</h4>
                                <span style="font-size: 0.8rem; color: #64748b;">{{ $db['user_type'] }} • {{ $db['email'] }} • Phone: {{ $db['phone'] }}</span>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                <span class="status-pill {{ $statusPillClass }}" style="white-space: nowrap;">{{ $db['approval_status'] }}</span>
                                <span style="font-size: 0.72rem; font-weight: 700; color: {{ $db['payment_status'] === 'Paid' ? '#166534' : '#b45309' }};">Payment: {{ $db['payment_status'] }}</span>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-bottom: 0.85rem; font-size: 0.82rem; color: #334155;">
                            <div>
                                <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Room / Workspace</strong>
                                <span style="font-weight: 700; color: #0f172a;"><i class="ph-bold ph-bed" style="color: var(--primary-color);"></i> {{ $db['room_name'] }}</span>
                            </div>
                            <div>
                                <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Guests & Tariff</strong>
                                <span style="font-weight: 700; color: #0f172a;"><i class="ph-bold ph-users"></i> {{ $db['no_of_persons'] }} Guests • ₹{{ $db['total_price'] }}</span>
                            </div>
                            <div style="grid-column: 1 / -1;">
                                <strong style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; display: block;">Stay Duration (Clock In &rarr; Clock Out)</strong>
                                <span style="font-weight: 600; color: #475569;"><i class="ph-bold ph-clock" style="color: var(--primary-color);"></i> {{ $db['clock_in'] }} &rarr; {{ $db['clock_out'] }}</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem; border-top: 1px solid #f8fafc;">
                            <a href="{{ $db['details_url'] }}" style="font-size: 0.82rem; font-weight: 800; color: var(--primary-color, #850f0f); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(133, 15, 15, 0.06); border-radius: 8px;">
                                View Full Details <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif
