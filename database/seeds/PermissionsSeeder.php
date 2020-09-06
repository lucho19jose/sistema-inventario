<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission category
    	Permission::create([
    		'name' => 'category.index',
    		'description' => 'Lista y navegacion sobre todas las categorías del sistema'
    	]);
    	Permission::create([
    		'name' => 'category.create',
    		'description' => 'Crear una nueva categoría'
    	]);
    	Permission::create([
    		'name' => 'category.update',
    		'description' => 'Editar cualquier dato de una categoría del sistema'
    	]);
    	Permission::create([
    		'name' => 'category.destroy',
    		'description' => 'Eliminar cualquier categorías del sistema'
    	]);

    	//Permission product
    	Permission::create([
    		'name' => 'product.index',
    		'description' => 'Lista y navegacion sobre todas los productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'product.create',
    		'description' => 'Crear un nuevo producto'
    	]);
    	Permission::create([
    		'name' => 'product.update',
    		'description' => 'Editar cualquier dato de un producto del sistema'
    	]);
    	Permission::create([
    		'name' => 'product.destroy',
    		'description' => 'Eliminar cualquier producto del sistema'
    	]);
    	Permission::create([
    		'name' => 'product.report',
    		'description' => 'Ver el reporte de stock disponible de productos del sistema'
    	]);

    	//Permission product
    	Permission::create([
    		'name' => 'provider.index',
    		'description' => 'Lista y navegacion sobre todas los proveedores del sistema'
    	]);
    	Permission::create([
    		'name' => 'provider.create',
    		'description' => 'Crear un nuevo proveedor'
    	]);
    	Permission::create([
    		'name' => 'provider.update',
    		'description' => 'Editar cualquier dato de un proveedor del sistema'
    	]);
    	Permission::create([
    		'name' => 'provider.destroy',
    		'description' => 'Eliminar cualquier proveedor del sistema'
    	]);

    	//Permission Branch
    	Permission::create([
    		'name' => 'branch.index',
    		'description' => 'Lista y navegacion sobre todas las sucursales del sistema'
    	]);
    	Permission::create([
    		'name' => 'branch.create',
    		'description' => 'Crear una nueva sucursal'
    	]);
    	Permission::create([
    		'name' => 'branch.update',
    		'description' => 'Editar cualquier dato de una sucursal del sistema'
    	]);
    	Permission::create([
    		'name' => 'branch.destroy',
    		'description' => 'Eliminar cualquier sucursal del sistema'
    	]);

    	//Permission Staff
    	Permission::create([
    		'name' => 'staff.index',
    		'description' => 'Lista y navegacion sobre todos los personales del sistema'
    	]);
    	Permission::create([
    		'name' => 'staff.create',
    		'description' => 'Crear un nuevo personal'
    	]);
    	Permission::create([
    		'name' => 'staff.update',
    		'description' => 'Editar cualquier dato de un personal del sistema'
    	]);
    	Permission::create([
    		'name' => 'staff.destroy',
    		'description' => 'Eliminar cualquier personal del sistema'
    	]);

    	//Permission User
    	Permission::create([
    		'name' => 'user.index',
    		'description' => 'Lista y navegacion sobre todos los usuarios del sistema'
    	]);
    	Permission::create([
    		'name' => 'user.create',
    		'description' => 'Crear un nuevo usuario'
    	]);
    	Permission::create([
    		'name' => 'user.update',
    		'description' => 'Editar el rol de un usuario del sistema'
    	]);
        Permission::create([
            'name' => 'user.reset',
            'description' => 'Restablecer la contraseña de un usuario del sistema'
        ]);
    	Permission::create([
    		'name' => 'user.destroy',
    		'description' => 'Eliminar cualquier usuario del sistema'
    	]);

        //Permission roles
        Permission::create([
            'name' => 'role.index',
            'description' => 'Lista y navegacion sobre todas los roles del sistema'
        ]);
        Permission::create([
            'name' => 'role.create',
            'description' => 'Crear un nuevo rol'
        ]);
        Permission::create([
            'name' => 'role.update',
            'description' => 'Editar cualquier dato de un rol del sistema'
        ]);
        Permission::create([
            'name' => 'role.destroy',
            'description' => 'Eliminar cualquier rol del sistema'
        ]);

    	//Permission Input
    	Permission::create([
    		'name' => 'input.index',
    		'description' => 'Lista y navegacion sobre todos los ingresos de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'input.create',
    		'description' => 'Crear un nuevo ingreso de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'input.show',
    		'description' => 'Ver a detalle cada ingreso de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'input.report',
    		'description' => 'Ver el reporte de ingreso de productos del sistema'
    	]);

    	//Permission Outputoutput
    	Permission::create([
    		'name' => 'output.index',
    		'description' => 'Lista y navegacion sobre todas las salidas de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'output.create',
    		'description' => 'Crear una nueva salida de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'output.show',
    		'description' => 'Ver a detalle cada salida de productos del sistema'
    	]);
    	Permission::create([
    		'name' => 'output.report',
    		'description' => 'Ver el reporte de salida de productos del sistema'
    	]);

    	//Asignacion de roles
    	$admin = Role::create(['name' => 'Administrador']);
    	$admin->givePermissionTo(Permission::all());

    	//Almacenero
    	$grocer = Role::create(['name' => 'Almacenero']);
    	$grocer->givePermissionTo([
    		'category.index',
            'category.create',
    		'product.index',
            'product.create',
            'product.update',
            'provider.index',
            'provider.create',
            'provider.update',
            'input.index',
            'input.create',
            'input.show',
            'output.index',
            'output.create',
            'output.show',
    	]);

    	//Asignar al usuario su rol
    	//Admin
    	$user = User::find(1);
    	$user->assignRole('Administrador');
    	//Almacenero
    	$user = User::find(2);
    	$user->assignRole('Almacenero');
    }
}
