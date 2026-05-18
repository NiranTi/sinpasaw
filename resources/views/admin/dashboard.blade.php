<h1>Dasbor admin ineh</h1>

<form action="{{ route('logout') }}" method="POST">
    @csrf

    <button type="submit">
        Logout
    </button>
</form>
