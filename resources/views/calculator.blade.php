<h2>Premium Calculator</h2>

<form method="POST" action="/calculate">
    @csrf
    Age: <input type="number" name="age"><br>
    Salary: <input type="number" name="salary"><br>
    Dependents: <input type="number" name="dependents"><br>
    <button type="submit">Calculate</button>
</form>

@if(isset($premium))
    <h3>Your Premium: ₹{{ $premium }}</h3>
@endif