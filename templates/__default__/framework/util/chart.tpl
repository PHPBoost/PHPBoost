<div class="chart-container chart-{type}">
    <canvas id="chart-{name}"></canvas>
</div>

<script>
    const ctx{name} = document.getElementById("chart-{name}");
    new Chart(ctx{name} , 
    {
        type: "{type}",
        data: {data},
        # IF C_OPTIONS #
        options: {options}
        # ENDIF #
    });
</script>
