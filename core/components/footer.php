<br>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        var selectElements = await document.querySelectorAll('select');
        var selectInstances = M.FormSelect.init(selectElements, {});

        var autocompleteElements = await document.querySelectorAll('.autocomplete');
        var autocompleteInstances = M.Autocomplete.init(autocompleteElements, {});

        var modalElements = await document.querySelectorAll('.modal');
        var modalInstances = M.Modal.init(modalElements, {});
    });
</script>
</html>