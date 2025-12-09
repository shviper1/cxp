<x-filament-widgets::widget>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- <x-slot name="heading">Recent Users</x-slot> -->



        <div style="display:grid; grid-template-columns:1fr; gap:24px; margin-top:10px;">

            <!-- Recent Users -->
            <div style="
        padding:20px;
        border:1px solid var(--card-border);
        border-radius:12px;
        background:var(--card-bg);
    "
                class="fi-wi-stats-overview-stat">
                <h3 style="font-size:18px; font-weight:600; margin-bottom:16px; color:var(--text-title);">Recent Users
                </h3>

                @foreach ($this->getRecentUsers() as $user)
                    <div
                        style="
                padding:14px;
                border:1px solid var(--inner-border);
                border-radius:12px;
                margin-bottom:12px;
                background:var(--inner-bg);
            ">
                        <!-- Row -->
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">

                            <!-- Avatar -->
                            <div
                                style="
                        width:36px;
                        height:36px;
                        background:#3b82f6;
                        color:#fff;
                        font-size:14px;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-weight:600;
                    ">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div style="flex:1; min-width:0;">
                                <p
                                    style="
                            font-size:14px;
                            font-weight:600;
                            color:var(--text-title);
                            margin:0;
                            white-space:nowrap;
                            overflow:hidden;
                            text-overflow:ellipsis;
                        ">
                                    {{ $user->name }}
                                </p>

                                <p
                                    style="
                            font-size:12px;
                            color:var(--text-sub);
                            margin:0;
                            white-space:nowrap;
                            overflow:hidden;
                            text-overflow:ellipsis;
                        ">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div style="display:flex; align-items:center; justify-content:space-between;">

                            @php
                                $colorBg =
                                    $user->verification_status === 'verified'
                                        ? 'var(--badge-green-bg)'
                                        : ($user->verification_status === 'pending'
                                            ? 'var(--badge-yellow-bg)'
                                            : 'var(--badge-gray-bg)');
                                $colorText =
                                    $user->verification_status === 'verified'
                                        ? 'var(--badge-green-text)'
                                        : ($user->verification_status === 'pending'
                                            ? 'var(--badge-yellow-text)'
                                            : 'var(--badge-gray-text)');
                            @endphp

                            <span
                                style="
                        padding:4px 8px;
                        border-radius:6px;
                        font-size:12px;
                        font-weight:600;
                        background:{{ $colorBg }};
                        color:{{ $colorText }};
                    ">
                                {{ ucfirst($user->verification_status ?? 'unverified') }}
                            </span>

                            <span style="font-size:12px; color:var(--text-sub);">
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recent Posts -->
            <div style="
        padding:20px;
        border:1px solid var(--card-border);
        border-radius:12px;
        background:var(--card-bg);
    "
                class="fi-wi-stats-overview-stat">
                <h3 style="font-size:18px; font-weight:600; margin-bottom:16px; color:var(--text-title);">Recent Posts
                </h3>

                @foreach ($this->getRecentPosts() as $post)
                    @php
                        $pBg =
                            $post->status === 'approved'
                                ? 'var(--badge-green-bg)'
                                : ($post->status === 'pending'
                                    ? 'var(--badge-yellow-bg)'
                                    : ($post->status === 'rejected'
                                        ? '#fecaca'
                                        : 'var(--badge-gray-bg)'));

                        $pText =
                            $post->status === 'approved'
                                ? 'var(--badge-green-text)'
                                : ($post->status === 'pending'
                                    ? 'var(--badge-yellow-text)'
                                    : ($post->status === 'rejected'
                                        ? '#991b1b'
                                        : 'var(--badge-gray-text)'));
                    @endphp

                    <div
                        style="
                padding:14px;
                border:1px solid var(--inner-border);
                border-radius:12px;
                margin-bottom:12px;
                background:var(--inner-bg);
            ">
                        <div style="display:flex; justify-content:space-between; gap:12px; margin-bottom:8px;">

                            <div style="flex:1; min-width:0;">
                                <p
                                    style="
                            font-size:14px;
                            font-weight:600;
                            margin:0 0 4px 0;
                            color:var(--text-title);
                            display:-webkit-box;
                            -webkit-line-clamp:2;
                            -webkit-box-orient:vertical;
                            overflow:hidden;
                        ">
                                    {{ $post->title }}
                                </p>

                                <p style="font-size:12px; color:var(--text-sub); margin:0;">
                                    by {{ $post->user->name ?? 'Unknown' }}
                                </p>
                            </div>

                            <span
                                style="
                        padding:4px 8px;
                        border-radius:6px;
                        font-size:12px;
                        font-weight:600;
                        flex-shrink:0;
                        background:{{ $pBg }};
                        color:{{ $pText }};
                    ">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>

                        <div
                            style="
                    border-top:1px solid var(--inner-border);
                    padding-top:8px;
                ">
                            <span style="font-size:12px; color:var(--text-sub);">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>



    </div>


</x-filament-widgets::widget>
