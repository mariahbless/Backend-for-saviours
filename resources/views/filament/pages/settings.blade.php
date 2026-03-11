<x-filament-panels::page>

    <div style="display: flex; flex-direction: column; gap: 24px;">

        {{-- ===== APPEARANCE ===== --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #EEF2FF; padding: 10px; border-radius: 10px; font-size: 20px;">🎨</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Appearance</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Customize how the dashboard looks</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #F9FAFB; border-radius: 12px; margin-bottom: 12px;">
                <div>
                    <p style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">🌙 Dark Mode</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 4px 0 0;">Switch between light and dark theme</p>
                </div>
                <label style="position: relative; display: inline-block; width: 52px; height: 28px;">
                    <input type="checkbox" id="darkModeToggle" style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleDarkMode()" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #CBD5E1; border-radius: 28px; transition: 0.3s;" id="darkModeSlider">
                        <span id="darkModeThumb" style="position: absolute; height: 22px; width: 22px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></span>
                    </span>
                </label>
            </div>
        </div>

        {{-- ===== NOTIFICATION PREFERENCES ===== --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #FFF7ED; padding: 10px; border-radius: 10px; font-size: 20px;">🔔</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Notification Preferences</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Choose what notifications you receive</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #F9FAFB; border-radius: 12px;">
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">📋 New Loan Applications</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 4px 0 0;">Get notified when a new loan is submitted</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 52px; height: 28px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span onclick="toggleSwitch(this)" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #3B82F6; border-radius: 28px; transition: 0.3s;">
                            <span style="position: absolute; height: 22px; width: 22px; left: 27px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></span>
                        </span>
                    </label>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #F9FAFB; border-radius: 12px;">
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">✅ Loan Approvals</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 4px 0 0;">Get notified when a loan is approved</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 52px; height: 28px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span onclick="toggleSwitch(this)" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #3B82F6; border-radius: 28px; transition: 0.3s;">
                            <span style="position: absolute; height: 22px; width: 22px; left: 27px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></span>
                        </span>
                    </label>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #F9FAFB; border-radius: 12px;">
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">❌ Loan Rejections</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 4px 0 0;">Get notified when a loan is rejected</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 52px; height: 28px;">
                        <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                        <span onclick="toggleSwitch(this)" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #CBD5E1; border-radius: 28px; transition: 0.3s;">
                            <span style="position: absolute; height: 22px; width: 22px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></span>
                        </span>
                    </label>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #F9FAFB; border-radius: 12px;">
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">👤 New User Registrations</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 4px 0 0;">Get notified when a new user registers</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 52px; height: 28px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span onclick="toggleSwitch(this)" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #3B82F6; border-radius: 28px; transition: 0.3s;">
                            <span style="position: absolute; height: 22px; width: 22px; left: 27px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></span>
                        </span>
                    </label>
                </div>

            </div>
        </div>

        {{-- ===== USER ROLES & PERMISSIONS ===== --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #F5F3FF; padding: 10px; border-radius: 10px; font-size: 20px;">🛡️</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">User Roles & Permissions</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Overview of access levels in the system</p>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background: #F9FAFB; color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 12px 16px; text-align: left;">Role</th>
                        <th style="padding: 12px 16px; text-align: left;">Access Level</th>
                        <th style="padding: 12px 16px; text-align: left;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-top: 1px solid #F3F4F6;">
                        <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">👑 Super Admin</td>
                        <td style="padding: 14px 16px;">
                            <span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Full Access</span>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280;">Can manage everything including settings</td>
                    </tr>
                    <tr style="border-top: 1px solid #F3F4F6; background: #FAFAFA;">
                        <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">🛡️ Admin</td>
                        <td style="padding: 14px 16px;">
                            <span style="background: #DBEAFE; color: #2563EB; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">High Access</span>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280;">Can manage loans and users</td>
                    </tr>
                    <tr style="border-top: 1px solid #F3F4F6;">
                        <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">📋 Loan Officer</td>
                        <td style="padding: 14px 16px;">
                            <span style="background: #DCFCE7; color: #16A34A; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Medium Access</span>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280;">Can view and approve loans only</td>
                    </tr>
                    <tr style="border-top: 1px solid #F3F4F6; background: #FAFAFA;">
                        <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">👁️ Viewer</td>
                        <td style="padding: 14px 16px;">
                            <span style="background: #F3F4F6; color: #4B5563; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Read Only</span>
                        </td>
                        <td style="padding: 14px 16px; color: #6B7280;">Can only view reports</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ===== MAINTENANCE MODE ===== --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #FFF7ED; padding: 10px; border-radius: 10px; font-size: 20px;">🔧</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Maintenance Mode</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Control app availability for users</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border-radius: 12px; background: {{ $maintenanceMode ? '#FEF2F2' : '#F0FDF4' }}; border: 1px solid {{ $maintenanceMode ? '#FECACA' : '#BBF7D0' }};">
                <div>
                    <p style="font-size: 14px; color: #4B5563; margin: 0 0 8px;">
                        When maintenance mode is <strong>ON</strong>, users cannot access the app.
                        The admin dashboard remains accessible.
                    </p>
                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: {{ $maintenanceMode ? '#FEE2E2' : '#DCFCE7' }};">
                        <span>{{ $maintenanceMode ? '🔴' : '🟢' }}</span>
                        <span style="font-size: 12px; font-weight: 700; color: {{ $maintenanceMode ? '#DC2626' : '#16A34A' }};">
                            {{ $maintenanceMode ? 'ON — App is down' : 'OFF — App is live' }}
                        </span>
                    </div>
                </div>

                <button
                    wire:click="toggleMaintenance"
                    style="background: {{ $maintenanceMode ? '#16A34A' : '#DC2626' }}; color: white; padding: 12px 24px; border-radius: 10px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                    {{ $maintenanceMode ? '✅ Turn OFF Maintenance' : '⚠️ Turn ON Maintenance' }}
                </button>
            </div>
        </div>

    </div>

    <script>
        function toggleSwitch(slider) {
            const thumb = slider.querySelector('span');
            const isOn = slider.style.background === 'rgb(59, 130, 246)';
            if (isOn) {
                slider.style.background = '#CBD5E1';
                thumb.style.left = '3px';
            } else {
                slider.style.background = '#3B82F6';
                thumb.style.left = '27px';
            }
        }

        function toggleDarkMode() {
            const slider = document.getElementById('darkModeSlider');
            const thumb = document.getElementById('darkModeThumb');
            const isOn = slider.style.background === 'rgb(59, 130, 246)';
            if (isOn) {
                slider.style.background = '#CBD5E1';
                thumb.style.left = '3px';
                document.documentElement.classList.remove('dark');
            } else {
                slider.style.background = '#3B82F6';
                thumb.style.left = '27px';
                document.documentElement.classList.add('dark');
            }
        }
    </script>

</x-filament-panels::page>