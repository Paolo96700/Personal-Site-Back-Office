<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{

    private $validations = [

        'type_id' => "required|integer|exists:types,id",
        'title' => 'required|string|min:5|max:50',
        'creation_date' => 'required|date|max:20',
        'last_update' => 'required|date|max:20',
        'author' => 'required|string|max:30',
        'collaborators' => 'nullable|string|max:150',
        'image' => 'nullable|image|max:10500',
        'description' => 'nullable|string',
        'link_github' => 'required|string|max:150',
        'technologies. *'   => 'integer|exists:technologies,id',

    ];
    private $validations_messages = [
        'required' => 'il campo :attribute è obbligatorio',
        'min' => 'il campo :attribute deve avere minimo :min caratteri',
        'max' => 'il campo :attribute non può superare i :max caratteri',
        'url' => 'il campo deve essere un url valido',
        'exists' => 'Valore non valido'
    ];


    public function index()
    {
        $projects = Project::Paginate(20);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $types = Type::All();
        $technologies = Technology::All();
        return view('admin.projects.create', compact('types', 'technologies'));
    }

    public function store(Request $request)
    {
        //validare i dati

        $request->validate($this->validations, $this->validations_messages);

        $data = $request->all();
        $image = null;

        //salvare l'immagine nella cartella degli uploads
        //prendere il percorso dell'immagine appena salvata
        if ($request->has('image')) {
            $image = Storage::put('uploads', $data['image']);
        }


        // salvare i dati nel database insieme al percorso dell'immagine

        $newProject = new Project();

        $newProject->type_id = $data['type_id'];
        $newProject->title = $data['title'];
        $newProject->slug = Project::slugger($data['title']);
        $newProject->creation_date = $data['creation_date'];
        $newProject->last_update = $data['last_update'];
        $newProject->author = $data['author'];
        $newProject->collaborators = $data['collaborators'];
        $newProject->image = $image;
        $newProject->description = $data['description'];
        $newProject->link_github = $data['link_github'];


        $newProject->save();

        $newProject->technologies()->sync($data['technologies'] ?? []);

        return redirect()->route('admin.projects.index', ['project' => $newProject]);
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        return view('admin.projects.show', compact('project'));
    }

    public function edit($slug)
    {

        $project = Project::where('slug', $slug)->firstOrFail();
        $types = Type::All();
        $technologies = Technology::All();

        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    public function update(Request $request, $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        //validare i dati del form
        $request->validate($this->validations, $this->validations_messages);
        $data = $request->all();


        // Faccio un controllo per fare in modo che se non viene modificata l'immagine, la vecchia non venga eliminata
        if (isset($data['image']))  // ($request->has('image'))// 
        {
            // salvo la nuova immagine
            $image = Storage::disk('public')->put('uploads', $data['image']);

            // elimino la vecchia immagine
            if ($project->image) {
                Storage::delete($project->image);
            }

            // aggiorno l'indirizzo della nuova immagine
            $project->image = $image;
        }

        // salvare i dati nel database se validi

        $project->type_id = $data['type_id'];
        $project->title = $data['title'];
        $project->creation_date = $data['creation_date'];
        $project->last_update = $data['last_update'];
        $project->author = $data['author'];
        $project->collaborators = $data['collaborators'];
        $project->description = $data['description'];
        $project->link_github = $data['link_github'];

        $project->update();

        $project->technologies()->sync($data['technologies'] ?? []);

        return to_route('admin.projects.show', ['project' => $project]);
    }

    public function destroy($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();


        $project->delete();

        return to_route('admin.projects.index')->with('delete_success', $project);
    }

    //da qui in avanti bisogna richiamare i route dal web.php perchè il comando si ferma a 'destroy'

    public function restore($slug)
    {
        //messo prima pechè altrimenti non ti fa comparire il messaggio di ripristino
        $project = Project::find($slug);

        Project::withTrashed()->where('slug', $slug)->restore();
        $project = Project::where('slug', $slug)->firstOrFail();


        return to_route('admin.projects.index')->with('restore_success', $project);
    }

    public function trashed()
    {
        $trashedProjects = Project::onlyTrashed()->paginate(5);

        return view('admin.projects.trashed', compact('trashedProjects'));
    }



    public function harddelete($slug)
    {
        $project = Project::withTrashed()->where('slug', $slug)->first();

        if ($project->file) {
            Storage::delete($project->file);
        }

        //disassociare tutti i tag dal project
        $project->technologies()->detach();
        $project->forceDelete();

        return to_route('admin.projects.trashed')->with('delete_success', $project);
    }

}
