<?php

$models = [
    'Category',
    'City',
    'Country',
    'Section',
    'SiteSetting',
    'State',
    'User',
];

foreach ($models as $model) {
    $policyFile = __DIR__ . "/app/Policies/{$model}Policy.php";

    if (file_exists($policyFile)) {
        echo "{$model}Policy already exists\n";
        continue;
    }

    $modelVar = strtolower($model);

    $content = "<?php

namespace App\Policies;

use App\Models\\{$model};
use App\Models\User;
use Illuminate\Auth\Access\Response;

class {$model}Policy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User \$user): bool
    {
        return \$user->can('ViewAny:{$model}');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User \$user, {$model} \${$modelVar}): bool
    {
        return \$user->can('View:{$model}');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User \$user): bool
    {
        return \$user->can('Create:{$model}');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User \$user, {$model} \${$modelVar}): bool
    {
        return \$user->can('Update:{$model}');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User \$user, {$model} \${$modelVar}): bool
    {
        return \$user->can('Delete:{$model}');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User \$user, {$model} \${$modelVar}): bool
    {
        return \$user->can('Restore:{$model}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User \$user, {$model} \${$modelVar}): bool
    {
        return \$user->can('ForceDelete:{$model}');
    }
}";

    file_put_contents($policyFile, $content);
    echo "Created {$model}Policy\n";
}

echo "Done!\n";
