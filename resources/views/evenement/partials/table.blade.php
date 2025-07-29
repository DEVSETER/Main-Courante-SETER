<div class="flex justify-between items-center mb-2">
    <h3 class="text-xl font-bold">{{ $titre }} dgcg</h3>

    {{-- Déclenche le bon modal par entité --}}
    @if(Str::contains($titre, 'SRCOF'))
        <button onclick="document.getElementById('modal-create-srcof').classList.remove('hidden')" class="btn">Ajouter SRCOF</button>
    @elseif(Str::contains($titre, 'CIV'))
        <button onclick="document.getElementById('modal-create-civ').classList.remove('hidden')" class="btn">Ajouter CIV</button>
    @endif
    {{-- Répète pour les autres entités --}}
</div>
