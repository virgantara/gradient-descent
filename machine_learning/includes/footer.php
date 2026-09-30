<footer class="site-footer">
    <div class="site-footer-inner">
        <span>Numerical & Machine Learning Interactive Lab</span>
        <span>&copy; <?= date('Y') ?></span>
    </div>
</footer>

<script>
document.querySelectorAll('.nav-dropdown-toggle').forEach(button => {
    button.addEventListener('click', event => {
        event.stopPropagation();
        button.parentElement.classList.toggle('open');
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.nav-dropdown.open').forEach(dropdown => {
        dropdown.classList.remove('open');
    });
});
</script>
</body>
</html>
