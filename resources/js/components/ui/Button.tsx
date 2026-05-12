interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
    size?: 'sm' | 'md' | 'lg';
    loading?: boolean;
}

export function Button({
    children,
    variant = 'primary',
    size = 'md',
    loading = false,
    disabled,
    className = '',
    ...props
}: ButtonProps) {
    const baseStyles = 'inline-flex items-center justify-center font-bold rounded-2xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed';
    
    const variants = {
        primary: 'bg-primary text-on-primary hover:bg-primary-dim shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5',
        secondary: 'bg-secondary text-on-secondary hover:bg-secondary/80 shadow-lg shadow-secondary/20',
        ghost: 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface',
        danger: 'bg-error/10 text-error hover:bg-error/20',
    };
    
    const sizes = {
        sm: 'px-4 py-2 text-xs',
        md: 'px-6 py-3 text-sm',
        lg: 'px-8 py-4 text-base',
    };

    return (
        <button
            {...props}
            disabled={disabled || loading}
            className={`${baseStyles} ${variants[variant]} ${sizes[size]} ${className}`}
        >
            {loading ? (
                <span className="w-5 h-5 border-2 border-current/30 border-t-current rounded-full animate-spin mr-2" />
            ) : null}
            {children}
        </button>
    );
}
