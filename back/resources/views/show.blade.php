<h2>Bulletin de paie - {{ $paie->mois }}</h2>
<p><strong>Collaborateur :</strong> {{ $paie->collaborateur->nom }}</p>
<p><strong>Salaire brut :</strong> {{ $paie->brut }}</p>
<p><strong>Retenue :</strong> {{ $paie->retenue }}</p>
<p><strong>Salaire net :</strong> {{ $paie->net }}</p>

<a href="{{ route('bulletin.download', $paie->id) }}">Télécharger le bulletin PDF</a>
