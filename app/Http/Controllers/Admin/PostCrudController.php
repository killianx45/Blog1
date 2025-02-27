<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.


        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);
        CRUD::setFromDb();

        CRUD::addField([
            'name' => 'categories',
            'label' => 'Catégories',
            'type' => 'select_multiple',
            'entity' => 'categories',
            'attribute' => 'name',
            'model' => 'App\Models\Category',
            'pivot' => true,
            'allows_multiple' => true,
        ]);

        CRUD::removeField('id_user');
        CRUD::removeField('slug');

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer un post.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'categories' => 'required|array|max:2',
            'categories.*' => 'distinct',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = Str::slug($post->title);
        $post->id_user = Auth::id();

        $post->save();

        if (is_array($request->categories)) {
            $post->categories()->sync($request->categories);
        }

        return redirect()->route('posts.index')->with('success', 'Post créé avec succès!');
    }

    public function destroy($id)
    {
        // Trouvez le post
        $post = Post::findOrFail($id);

        // Supprimez les commentaires associés
        $post->comments()->delete();

        // Supprimez le post
        $post->delete();

        return redirect()->route('posts.show')->with('success', 'Post et commentaires supprimés avec succès!');
    }
}
