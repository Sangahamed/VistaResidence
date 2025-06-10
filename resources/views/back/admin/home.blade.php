@extends('back.admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Active Properties -->
                    <div class="bg-stats-purple text-white rounded-lg p-6">
                        <h3 class="text-lg font-medium">Active properties</h3>
                        <p class="text-4xl font-bold mt-2">21</p>
                    </div>

                    <!-- Pending Properties -->
                    <div class="bg-stats-turquoise text-white rounded-lg p-6">
                        <h3 class="text-lg font-medium">Pending properties</h3>
                        <p class="text-4xl font-bold mt-2">0</p>
                    </div>

                    <!-- Expired Properties -->
                    <div class="bg-stats-red text-white rounded-lg p-6">
                        <h3 class="text-lg font-medium">Expired properties</h3>
                        <p class="text-4xl font-bold mt-2">0</p>
                    </div>

                    <!-- Agents -->
                    <div class="bg-stats-blue text-white rounded-lg p-6">
                        <h3 class="text-lg font-medium">Agents</h3>
                        <p class="text-4xl font-bold mt-2">22</p>
                    </div>
                </div>

                <!-- Analytics Sections -->
                <div class="mt-6">
                    <!-- Site Analytics -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Site Analytics</h3>
                            <select class="border rounded px-2 py-1">
                                <option>Today</option>
                                <option>Yesterday</option>
                                <option>Last 7 days</option>
                            </select>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>

                    <!-- Top Most Visit Pages -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Top Most Visit Pages</h3>
                            <select class="border rounded px-2 py-1">
                                <option>Today</option>
                                <option>Yesterday</option>
                                <option>Last 7 days</option>
                            </select>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>

                    <!-- Top Browsers -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Top Browsers</h3>
                            <select class="border rounded px-2 py-1">
                                <option>Today</option>
                                <option>Yesterday</option>
                                <option>Last 7 days</option>
                            </select>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>

                    <!-- Top Referrers -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Top Referrers</h3>
                            <select class="border rounded px-2 py-1">
                                <option>Today</option>
                                <option>Yesterday</option>
                                <option>Last 7 days</option>
                            </select>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Recent Posts</h3>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>

                    <!-- Activities Logs -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Activities Logs</h3>
                        </div>
                        <div class="text-gray-500">Network Error</div>
                    </div>
                </div>
            </div>
            <div class="text-center text-sm text-gray-500 py-4">
                Page loaded in 5.97 seconds<br>
                Copyright 2024 © Immediate - Version 1.7.4
            </div>
@endsection