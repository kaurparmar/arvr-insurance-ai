<h1>Insurance Plans</h1>

@foreach($plans as $plan)
    <div>
        <h3>{{ $plan->name }}</h3>
        <p>Premium: ₹{{ $plan->premium }}</p>
        <a href="/plans/{{ $plan->id }}">View</a>
    </div>
@endforeach