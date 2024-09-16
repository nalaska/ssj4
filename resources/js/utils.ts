export function formatBelt(belt: string): string {
    if (!belt) {
        return 'Inconnu'; 
    }
    return belt.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

export async function deleteUser(userId: number, csrfToken: string): Promise<void> {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        return;
    }

    try {
        const response = await fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            console.error('Erreur lors de la suppression de l\'utilisateur');
        }
    } catch (error) {
        console.error('Erreur lors de la suppression de l\'utilisateur', error);
    }
}