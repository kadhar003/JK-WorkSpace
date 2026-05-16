<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <x-app-layout>

<div class="flex h-screen bg-gray-900 text-white">

    <!-- SIDEBAR -->
    <div class="w-72 bg-gray-800 p-4 overflow-y-auto">

        <h2 class="text-2xl font-bold mb-6">
            Project Files
        </h2>

        @foreach($files as $file)

            <div
                onclick="openFile({{ $file->id }})"
                class="p-3 rounded-lg hover:bg-gray-700 cursor-pointer mb-2"
            >

                {{ $file->name }}

            </div>

        @endforeach

    </div>

    <!-- EDITOR AREA -->
    <div class="flex-1 flex flex-col">

        <!-- TOP BAR -->
        <div class="bg-gray-800 p-4 border-b border-gray-700">

            <h1
                id="currentFileName"
                class="text-xl font-bold"
            >
                Select File
            </h1>

        </div>

        <!-- EDITOR -->
        <div id="editor" class="flex-1"></div>

        <!-- SAVE BUTTON -->
        <div class="bg-gray-800 p-4">

            <button
                onclick="saveFile()"
                class="bg-blue-500 px-6 py-2 rounded-lg"
            >
                Save File
            </button>

        </div>

    </div>

</div>

<script>

let currentFileId = null;

const editor = monaco.editor.create(
    document.getElementById('editor'),
    {

        value: '',

        language: 'javascript',

        theme: 'vs-dark',

        automaticLayout: true

    }
);

// OPEN FILE
function openFile(fileId)
{
    fetch('/files/' + fileId)

    .then(res => res.json())

    .then(file => {

        currentFileId = file.id;

        document.getElementById(
            'currentFileName'
        ).innerHTML = file.name;

        editor.setValue(file.content || '');

    });
}

// SAVE FILE
function saveFile()
{
    fetch('/files/save', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}'

        },

        body: JSON.stringify({

            file_id: currentFileId,

            content: editor.getValue()

        })

    })

    .then(res => res.json())

    .then(data => {

        alert('File Saved');

        // REALTIME UPDATE
        window.socket.emit(
            'code-update',
            data.file
        );

    });
}

// REALTIME RECEIVE
window.socket.on('code-update', (file) => {

    if(file.id === currentFileId) {

        editor.setValue(file.content);

    }

});

</script>

</x-app-layout>
</body>
</html>