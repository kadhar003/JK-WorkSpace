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

<div class="p-8">

    <h1 class="text-3xl font-bold mb-6">
        Collaborative Notes
    </h1>

    @foreach($notes as $note)

        <div class="mb-8">

            <h2 class="text-2xl font-bold mb-2">
                {{ $note->title }}
            </h2>

            <textarea
                id="note-{{ $note->id }}"
                class="w-full h-64 border rounded-xl p-4"
                onkeyup="updateNote({{ $note->id }})"
            >{{ $note->content }}</textarea>

        </div>

    @endforeach

</div>

<script>

function updateNote(noteId)
{
    let content = document.getElementById('note-' + noteId).value;

    fetch('/notes/update', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            note_id: noteId,
            content: content
        })

    })
    .then(res => res.json())
    .then(data => {

        window.socket.emit('note-updated', data);

    });
}

window.socket.on('note-updated', (note) => {

    let textarea = document.getElementById('note-' + note.id);

    if(textarea) {

        textarea.value = note.content;

    }

});

</script>

</x-app-layout>
</body>
</html>