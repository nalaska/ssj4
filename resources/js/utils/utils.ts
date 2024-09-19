export function formatBelt(belt: string): string {
    if (!belt) {
        return 'Inconnu'; 
    }
    return belt.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

export const belts = [
    'blanche',
    'grise',
    'jaune',
    'orange',
    'verte',
    'bleu',
    'violette',
    'marron',
    'noire'
];

export const roles = {
    administrateur: 'Administrateur',
    professeur: 'Professeur',
    eleve: 'Élève',
};