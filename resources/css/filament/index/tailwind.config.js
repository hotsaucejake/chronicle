import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Index/**/*.php',
        './resources/views/filament/index/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
