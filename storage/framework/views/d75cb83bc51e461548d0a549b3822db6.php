

<?php
    $appEnv = app()->environment();
    $isLocal = $appEnv === 'local';
    $isProduction = $appEnv === 'production';

    // Check if hot file exists (for debug info)
    $hotFileExists = file_exists(public_path('hot'));

    // Smart detection: Use Vite ONLY if:
    // 1. We are in local environment
    // 2. Vite dev server is running (hot file exists)
    // 3. User hasn't explicitly disabled it
    $useVite = $isLocal &&
               $hotFileExists &&
               config('assets.use_vite_in_dev', true);

    // For production or when Vite is not available
    $useProductionAssets = $isProduction || !$useVite;
?>


<link rel="preconnect" href="https://fonts.bunny.net">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$useVite): ?>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($useVite): ?>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php else: ?>
    

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('css/app-production-full.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app-production-full.css')); ?>?v=<?php echo e(filemtime(public_path('css/app-production-full.css'))); ?>">
    <?php elseif($isProduction && file_exists(public_path('css/app-production-optimized-clean.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app-production-optimized-clean.css')); ?>?v=<?php echo e(filemtime(public_path('css/app-production-optimized-clean.css'))); ?>">
    <?php elseif($isProduction && file_exists(public_path('css/app-production-optimized.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app-production-optimized.css')); ?>?v=<?php echo e(filemtime(public_path('css/app-production-optimized.css'))); ?>">
    <?php elseif($isProduction && file_exists(public_path('css/app-production-final.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app-production-final.css')); ?>?v=<?php echo e(filemtime(public_path('css/app-production-final.css'))); ?>">
    <?php elseif(file_exists(public_path('css/app-production.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app-production.css')); ?>?v=<?php echo e(filemtime(public_path('css/app-production.css'))); ?>">
    <?php elseif(file_exists(public_path('css/app.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>?v=<?php echo e(filemtime(public_path('css/app.css'))); ?>">
    <?php else: ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/assets'))): ?>
            <?php
                $manifestPath = public_path('build/.vite/manifest.json');
                if (file_exists($manifestPath)) {
                    $manifest = json_decode(file_get_contents($manifestPath), true);
                    $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                    if ($cssFile) {
                        echo '<link rel="stylesheet" href="' . asset("build/{$cssFile}") . '">';
                    }
                }
            ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('css/display-fix.css'))): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/display-fix.css')); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($GLOBALS['alpine_loaded'])): ?>
        <?php $GLOBALS['alpine_loaded'] = true; ?>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($GLOBALS['axios_loaded'])): ?>
        <?php $GLOBALS['axios_loaded'] = true; ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js" integrity="sha512-b94Z6431JyXY14iSXwgzeZurHHRNkLt9d6bAHt7BZT38eqV+GyngIi/tVye4jBKPYQ2lBdRs0glww4fmpuLRwA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('js/app-consolidated.js'))): ?>
        <script src="<?php echo e(asset('js/app-consolidated.js')); ?>?v=<?php echo e(filemtime(public_path('js/app-consolidated.js'))); ?>" defer></script>
    <?php elseif($isProduction && file_exists(public_path('js/app-production-optimized.js'))): ?>
        <script src="<?php echo e(asset('js/app-production-optimized.js')); ?>?v=<?php echo e(filemtime(public_path('js/app-production-optimized.js'))); ?>" defer></script>
    <?php elseif($isProduction && file_exists(public_path('js/app-production-final.js'))): ?>
        <script src="<?php echo e(asset('js/app-production-final.js')); ?>?v=<?php echo e(filemtime(public_path('js/app-production-final.js'))); ?>" defer></script>
    <?php elseif(file_exists(public_path('js/app.js'))): ?>
        <script src="<?php echo e(asset('js/app.js')); ?>?v=<?php echo e(filemtime(public_path('js/app.js'))); ?>" defer></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('js/countdown-function.js'))): ?>
        <script src="<?php echo e(asset('js/countdown-function.js')); ?>"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('js/youtube-fix-enhanced.js'))): ?>
        <script src="<?php echo e(asset('js/youtube-fix-enhanced.js')); ?>"></script>
    <?php elseif(file_exists(public_path('js/youtube-fix.js'))): ?>
        <script src="<?php echo e(asset('js/youtube-fix.js')); ?>"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('js/image-fallback.js'))): ?>
        <script src="<?php echo e(asset('js/image-fallback.js')); ?>" defer></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>



<style>
    [x-cloak] { display: none !important; }
    [wire\:loading] { display: inline-block; }
    [wire\:loading].hidden { display: none !important; }
    body { font-family: 'Figtree', system-ui, sans-serif; }

    /* Fix YouTube embed responsive */
    .youtube-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .youtube-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>


<script>
    // Ensure Alpine is only initialized once
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Alpine && !window.Alpine._initialized) {
            window.Alpine._initialized = true;
        }
    });

    // Prevent console errors in production
    <?php if($isProduction): ?>
    if (typeof console !== 'undefined') {
        console.log = function() {};
        console.warn = function() {};
        console.info = function() {};
    }
    <?php endif; ?>
</script>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLocal && !$isProduction): ?>
    <?php
        $debugInfo = [
            'environment' => $appEnv,
            'useVite' => $useVite,
            'hotFileExists' => $hotFileExists,
            'productionAssets' => $useProductionAssets
        ];
    ?>
    <script>
        console.log('Asset Loader Mode:', <?php echo json_encode($debugInfo, 15, 512) ?>);
    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/components/asset-loader.blade.php ENDPATH**/ ?>