@props(['value', 'caption'])

<figure class="pie-chart">
    <div class="pie-chart__wrapper">
        <div
            class="pie-chart__chart"
            style="--p: {{$value}};"
        ></div>
    </div>
    <div class="pie-chart__value">{{ $slot }}</div>
    <figcaption class="pie-chart__caption">{{ $caption }}</figcaption>
</figure>