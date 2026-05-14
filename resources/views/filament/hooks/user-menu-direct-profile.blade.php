<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileUrl = @json(\App\Filament\Pages\EditProfile::getUrl());

        setTimeout(() => {
            const userMenuButton = document.querySelector('.fi-user-menu button');

            if (!userMenuButton) return;

            userMenuButton.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                window.location.href = profileUrl;
            }, true);
        }, 500);
    });
</script>
