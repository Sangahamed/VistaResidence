<?php
    $value = fn($field, $default = '') => old($field, $property->$field ?? $default);
    $isEdit = isset($property);
?>

<div class="row">
    <div class="col-md-8">
        
        <?php if (isset($component)) { $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.form-section','data' => ['title' => 'Informations de base']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.form-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Informations de base']); ?>
            <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'title','label' => 'Titre','required' => true,'value' => $value('title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','label' => 'Titre','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('title'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal721c554cbdba1062ead8507be1e8a7c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal721c554cbdba1062ead8507be1e8a7c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.textarea','data' => ['name' => 'description','label' => 'Description','value' => $value('description')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Description','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('description'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal721c554cbdba1062ead8507be1e8a7c9)): ?>
<?php $attributes = $__attributesOriginal721c554cbdba1062ead8507be1e8a7c9; ?>
<?php unset($__attributesOriginal721c554cbdba1062ead8507be1e8a7c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal721c554cbdba1062ead8507be1e8a7c9)): ?>
<?php $component = $__componentOriginal721c554cbdba1062ead8507be1e8a7c9; ?>
<?php unset($__componentOriginal721c554cbdba1062ead8507be1e8a7c9); ?>
<?php endif; ?>

            <div class="row">
                <?php if (isset($component)) { $__componentOriginal208c8c60fa8cc72194c0b6031f82da1e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.select','data' => ['name' => 'type','label' => 'Type de bien','required' => true,'options' => [
                    'apartment' => 'Appartement',
                    'house' => 'Maison',
                    'villa' => 'Villa',
                    'land' => 'Terrain',
                    'commercial' => 'Local commercial',
                    'office' => 'Bureau',
                ],'selected' => $value('type'),'class' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'type','label' => 'Type de bien','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'apartment' => 'Appartement',
                    'house' => 'Maison',
                    'villa' => 'Villa',
                    'land' => 'Terrain',
                    'commercial' => 'Local commercial',
                    'office' => 'Bureau',
                ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('type')),'class' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e)): ?>
<?php $attributes = $__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e; ?>
<?php unset($__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal208c8c60fa8cc72194c0b6031f82da1e)): ?>
<?php $component = $__componentOriginal208c8c60fa8cc72194c0b6031f82da1e; ?>
<?php unset($__componentOriginal208c8c60fa8cc72194c0b6031f82da1e); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal208c8c60fa8cc72194c0b6031f82da1e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.select','data' => ['name' => 'status','label' => 'Statut','required' => true,'options' => [
                    'for_sale' => 'À vendre',
                    'for_rent' => 'À louer',
                    'sold' => 'Vendu',
                    'rented' => 'Loué',
                ],'selected' => $value('status'),'class' => 'col-md-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','label' => 'Statut','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'for_sale' => 'À vendre',
                    'for_rent' => 'À louer',
                    'sold' => 'Vendu',
                    'rented' => 'Loué',
                ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('status')),'class' => 'col-md-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e)): ?>
<?php $attributes = $__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e; ?>
<?php unset($__attributesOriginal208c8c60fa8cc72194c0b6031f82da1e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal208c8c60fa8cc72194c0b6031f82da1e)): ?>
<?php $component = $__componentOriginal208c8c60fa8cc72194c0b6031f82da1e; ?>
<?php unset($__componentOriginal208c8c60fa8cc72194c0b6031f82da1e); ?>
<?php endif; ?>
            </div>

            <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'price','label' => 'Prix (€)','type' => 'number','step' => '0.01','required' => true,'value' => $value('price'),'icon' => '€']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'price','label' => 'Prix (€)','type' => 'number','step' => '0.01','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('price')),'icon' => '€']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $attributes = $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $component = $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>

        
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Localisation</h3>
            
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('address-autocomplete', [
                'address' => $property->address ?? '',
                'street' => $property->street ?? '',
                'city' => $property->city ?? '',
                'postalCode' => $property->postal_code ?? '',
                'country' => $property->country ?? '',
                'latitude' => $property->latitude ?? null,
                'longitude' => $property->longitude ?? null,
            ]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1007109557-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>

        
        <?php if (isset($component)) { $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.form-section','data' => ['title' => 'Caractéristiques']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.form-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Caractéristiques']); ?>
            <div class="row">
                <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'bedrooms','label' => 'Chambres','type' => 'number','class' => 'col-md-6','value' => $value('bedrooms')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bedrooms','label' => 'Chambres','type' => 'number','class' => 'col-md-6','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('bedrooms'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'bathrooms','label' => 'Salles de bain','type' => 'number','class' => 'col-md-6','value' => $value('bathrooms')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bathrooms','label' => 'Salles de bain','type' => 'number','class' => 'col-md-6','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('bathrooms'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
            </div>

            <div class="row">
                <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'area','label' => 'Surface (m²)','type' => 'number','step' => '0.01','class' => 'col-md-6','value' => $value('area')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'area','label' => 'Surface (m²)','type' => 'number','step' => '0.01','class' => 'col-md-6','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('area'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'year_built','label' => 'Année de construction','type' => 'number','class' => 'col-md-6','value' => $value('year_built')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'year_built','label' => 'Année de construction','type' => 'number','class' => 'col-md-6','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value('year_built'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
            </div>

            <?php
                $featuresList = [
                    'garage' => 'Garage', 'parking' => 'Parking', 'garden' => 'Jardin',
                    'terrace' => 'Terrasse', 'balcony' => 'Balcon', 'pool' => 'Piscine',
                    'elevator' => 'Ascenseur', 'air_conditioning' => 'Climatisation',
                    'heating' => 'Chauffage', 'security_system' => 'Sécurité',
                    'storage' => 'Stockage', 'furnished' => 'Meublé',
                ];
                $featuresSelected = old('features', $property->features ?? []);
            ?>

            <div class="row">
                <?php $__currentLoopData = $featuresList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="feature_<?php echo e($key); ?>" name="features[]" value="<?php echo e($key); ?>"
                                <?php echo e(in_array($key, $featuresSelected) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="feature_<?php echo e($key); ?>"><?php echo e($label); ?></label>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                    <?php echo e(old('is_featured', $property->is_featured ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="is_featured">
                    Mettre en avant cette propriété
                </label>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $attributes = $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $component = $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>

        
        <?php if(!$isEdit): ?>
        <?php if (isset($component)) { $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.form-section','data' => ['title' => 'Médias']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.form-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Médias']); ?>
            <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'images[]','label' => 'Images','type' => 'file','multiple' => true,'accept' => 'image/*']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'images[]','label' => 'Images','type' => 'file','multiple' => true,'accept' => 'image/*']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.input','data' => ['name' => 'videos[]','label' => 'Vidéos','type' => 'file','multiple' => true,'accept' => 'video/*']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'videos[]','label' => 'Vidéos','type' => 'file','multiple' => true,'accept' => 'video/*']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $attributes = $__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__attributesOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b)): ?>
<?php $component = $__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b; ?>
<?php unset($__componentOriginaldce2d65dd04f8cb6e87c9e5d5bbbed8b); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $attributes = $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $component = $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        
        <?php if(auth()->user()->companies->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property.form-section','data' => ['title' => 'Entreprise']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property.form-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Entreprise']); ?>
            <label for="company_id" class="form-label">Associer à une entreprise</label>
            <select class="form-select" name="company_id" id="company_id">
                <option value="">Aucune entreprise</option>
                <?php $__currentLoopData = auth()->user()->companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                        <?php echo e($company->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $attributes = $__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__attributesOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c)): ?>
<?php $component = $__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c; ?>
<?php unset($__componentOriginaldaefdc84751e6bb2bd5d75ef2bad1e9c); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/partials/form.blade.php ENDPATH**/ ?>