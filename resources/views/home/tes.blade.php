@extends('home.layout.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center>

            <div class="card">

                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('send.notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>

                        <div class="form-group">
                            <label>Body</label>
                            <textarea class="form-control" name="body"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>
                    <div id="tampil_token"></div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')

<!-- Firebase SDK with modular API -->
<script type="module">
    // Import the required Firebase modules
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/11.1.0/firebase-app.js';
    import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging.js';

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyBHfEqkh7b4-eURI4KbGTTyuSDK64p_E2Q",
    authDomain: "pushtime-3c5d0.firebaseapp.com",
    projectId: "pushtime-3c5d0",
    storageBucket: "pushtime-3c5d0.firebasestorage.app",
    messagingSenderId: "246045605851",
    appId: "1:246045605851:web:621edb51bac44b93b131fe"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    // Function to register for notifications
    window.initFirebaseMessagingRegistration = function () {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log("Notification permission granted.");

                // Get the FCM token using getToken
                getToken(messaging, { vapidKey: 'BNGMZIKhjFK-T8F8XYusUe-7p0B3P8g2J0-RkkRlMwZ-mz2GZ1SwNpeR04f4PQ5gJJqrdzAIAThZmYq3-WPxAb4' })  // Replace with your actual VAPID key
                    .then((token) => {
                        if (token) {
                            console.log("FCM Token:", token);
                            $("#loading").show();

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                url: "{{ route('save-token') }}",
                                type: 'POST',
                                data: {
                                    token: token
                                },
                                dataType: 'JSON',
                                success: function (response) {
                                    $("#btn-nft-enable").attr('disabled', true);
                                    $("#loading").hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Notifikasi Aktif',
                                        text: 'Anda akan menerima Notifikasi jika terdapat Upload Pembayaran dari Penghuni.'
                                    });
                                },
                                error: function (err) {
                                    $("#btn-nft-enable").attr('disabled', false);
                                    $("#loading").hide();
                                    console.error("Error saving token:", err);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal menyimpan token. Silakan coba lagi.'
                                    });
                                },
                            });
                        }
                    })
                    .catch((err) => {
                        console.error("Error getting token:", err);
                        $("#btn-nft-enable").attr('disabled', false);
                        $("#loading").hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mendapatkan token. Silakan coba lagi.'
                        });
                    });
            } else {
                console.error("Notification permission denied.");
            }
        });
    }

    // Listen for messages when the app is in the foreground
    onMessage(messaging, function (payload) {
        console.log("Message received. ", payload);
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });
</script>

@endsection
