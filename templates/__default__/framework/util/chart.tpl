<div class="chart-container chart-{TYPE}">
    <canvas id="chart-{NAME}"></canvas>
</div>

<script>
    const ctx{NAME} = document.getElementById("chart-{NAME}");
    new Chart(ctx{NAME} , 
    {
        type: "{TYPE}",
        data: {DATA},
        # IF C_OPTIONS #
        options: {OPTIONS}
        # ENDIF #
    });
</script>
