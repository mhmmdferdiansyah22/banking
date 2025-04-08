@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 70px;">
        <h2>Pocket</h2>

        @if (Auth::user()->role->name == 'bank')
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm p-3 text-white text-center" style="background: #1e3a8a; border-radius: 12px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#topupModal">
                    <div class="d-flex justify-content-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-wallet fs-3 text-primary"></i>
                        </div>
                    </div>
                    <h6 class="mt-2 text-white">Top-Up</h6>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm p-3 text-center" style="border-radius: 12px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                    <div class="d-flex justify-content-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-upload fs-4 text-danger"></i>
                        </div>
                    </div>
                    <h6 class="mt-2 text-muted">Withdraw</h6>
                </div>
            </div>
        </div>
        @endif

        <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Top-Up</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pocket.topup') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="text" name="account_number" class="form-control mb-3" placeholder="Enter account number" required>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Withdraw Modal -->
        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Withdraw</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pocket.withdraw') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="text" name="account_number" class="form-control mb-3" placeholder="Enter account number" required>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-8"> --}}
            <div class="card mt-3 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Transaction History</h5>
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
                                • {{ $transaction->user->name }} | {{ $transaction->created_at->format('d-m-Y H:i:s') }}
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
                        <div class="text-end" style="min-width: 180px;">
                            <div class="fw-bold mb-1">
                                @if ($transaction->type == 'topup' || $transaction->type == 'receive')
                                    <span class="text-primary">+ Rp {{ number_format($transaction->amount, 2) }}</span>
                                @elseif($transaction->type == 'withdraw' || $transaction->type == 'transfer')
                                    <span class="text-danger">- Rp {{ number_format($transaction->amount, 2) }}</span>
                                @else
                                    <span>Rp {{ number_format($transaction->amount, 2) }}</span>
                                @endif
                            </div>

                            @if ($transaction->status == 'pending' && Auth::user()->role->name == 'bank')
                                <div class="d-flex gap-1 justify-content-end">
                                    <form action="{{ route('wallet.updateStatus', $transaction->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                    </form>

                                    <form action="{{ route('wallet.updateStatus', $transaction->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="mt-3">
                <div class="pagination justify-content-center">
                    {{ $wallet->links('pagination::bootstrap-4') }}
                </div>
            </div> --}}
        {{-- </div> --}}
    </div>
@endsection
