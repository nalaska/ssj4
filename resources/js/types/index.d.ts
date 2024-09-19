export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    phone?: string; 
    belt: string; 
    year_of_registration: number; 
    status: string; 
    roles: string[]; 
    date_of_birth?: string; 
    picture?: string; 
    attendance?: Record<string, any>; 
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

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