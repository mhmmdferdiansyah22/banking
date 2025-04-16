@extends('layouts.app')

@section('content')
    @if (Auth::user()->role->name == 'student')
        <div class="container" style="margin-top: 70px;">
            <div class="row g-4">
                <!-- Left Section: Balance, Income, Expenses, and Transactions -->
                <div class="col-lg-8">
                    <!-- Balance, Income, Expenses Cards -->
                    <div class="row g-3">
                        <!-- Balance Card -->
                        <div class="col-md-4">
                            <div class="card shadow-sm p-3 text-white text-center"
                                style="background: #1e3a8a; border-radius: 12px;">
                                <div class="d-flex justify-content-center">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-wallet fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <h6 class="mt-2">Balance</h6>
                                <p class="fw-bold fs-5">Rp {{ number_format($balance) }}</p>
                            </div>
                        </div>

                        <!-- Income Card -->
                        <div class="col-md-4">
                            <div class="card shadow-sm p-3 text-center" style="border-radius: 12px;">
                                <div class="d-flex justify-content-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-hand-holding-dollar fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <h6 class="mt-2 text-muted">Income</h6>
                                <p class="fw-bold fs-5">Rp {{ number_format($income) }}</p>
                            </div>
                        </div>

                        <!-- Expenses Card -->
                        <div class="col-md-4">
                            <div class="card shadow-sm p-3 text-center" style="border-radius: 12px;">
                                <div class="d-flex justify-content-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-money-bill-trend-up fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <h6 class="mt-2 text-muted">Expenses</h6>
                                <p class="fw-bold fs-5">Rp {{ number_format($outcome) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction History -->
                    <div class="card mt-3 shadow-sm p-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                            <h5 class="mb-2 mb-md-0">Recent Transactions</h5>
                            
                            <form method="GET" class="w-100 w-md-auto" style="max-width: 350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Search"
                                        value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>    
                        

                        @foreach ($wallet as $transaction)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <!-- Info transaksi kiri -->
                                <div>
                                    <div class="fw-bold">
                                        @if ($transaction->type == 'transfer' || $transaction->type == 'receive')
                                            {{ $transaction->description }}
                                        @else
                                            {{ ucfirst($transaction->type) }}
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        • {{ $transaction->user->name }} |
                                        {{ $transaction->created_at->format('d F Y, h:i A') }}
                                    </div>
                                    <div class="small">
                                        @if ($transaction->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($transaction->status == 'accepted')
                                            <span class="badge bg-primary">Accepted</span>
                                        @elseif ($transaction->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Nominal transaksi kanan -->
                                <div class="text-end" style="min-width: 140px;">
                                    <div class="fw-bold">
                                        @if ($transaction->type == 'topup' || $transaction->type == 'receive')
                                            <span class="text-primary">+ Rp
                                                {{ number_format($transaction->amount, 2) }}</span>
                                        @elseif($transaction->type == 'withdraw' || $transaction->type == 'transfer')
                                            <span class="text-danger">- Rp
                                                {{ number_format($transaction->amount, 2) }}</span>
                                        @else
                                            <span>Rp {{ number_format($transaction->amount, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Section: My Cards & Actions -->
                <div class="col-md-4">
                    <div class="card shadow-sm p-4">
                        <h4 class="mb-3">My Card</h4>

                        <div class="visa-card rounded-3 p-4 text-white"
                            style="background: linear-gradient(135deg, #172554, #1e40af); width: 100%; height: 200px;">
                            <div class="fs-5 text-end fst-italic fw-bold">Avobank</div>
                            <div class="card-number mt-4" style="letter-spacing: 2px; font-size: 18px;">
                                {{ $account_number ?? '**** **** **** ****' }}
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="mt-2">{{ Auth::user()->name }}</span>
                                <div class="fs-4 fst-italic fw-bold">VISA</div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button class="btn btn-light shadow-sm d-flex flex-column align-items-center p-3 rounded-3"
                                data-bs-toggle="modal" data-bs-target="#topupModal" style="width: 120px;">
                                <i class="fa-solid fa-plus fs-4 text-muted"></i>
                                <span class="text-muted small">Top-up</span>
                            </button>

                            <button class="btn btn-light shadow-sm d-flex flex-column align-items-center p-3 rounded-3"
                                data-bs-toggle="modal" data-bs-target="#withdrawModal" style="width: 120px;">
                                <i class="fas fa-upload fs-4 text-danger"></i>
                                <span class="text-muted small">Withdraw</span>
                            </button>

                            <button class="btn btn-light shadow-sm d-flex flex-column align-items-center p-3 rounded-3"
                                data-bs-toggle="modal" data-bs-target="#transferModal" style="width: 120px;">
                                <i class="fas fa-exchange-alt fs-4 text-primary"></i>
                                <span class="text-muted small">Transfer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @foreach (['topup' => 'success', 'withdraw' => 'danger', 'transfer' => 'primary'] as $action => $color)
            <div class="modal fade" id="{{ $action }}Modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-{{ $color }}">{{ ucfirst($action) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('wallet.' . $action) }}" method="POST">
                                @csrf
                                @if ($action == 'transfer')
                                    <input type="text" name="account_number" class="form-control mb-2"
                                        placeholder="Recipient's account number" required>
                                @endif
                                <input type="number" name="amount" class="form-control" placeholder="Enter amount"
                                    required>
                                <button type="submit" class="btn btn-{{ $color }} mt-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <script>
            alert('Mingger Lu Misqueen');
            window.location = "/pocket";
        </script>
    @endif
@endsection
