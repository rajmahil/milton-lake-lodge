import { cn } from '../../utils/tailwind-merge.jsx';

function Input( { className, type, ...props } ) {
	return (
		<input
			type={ type }
			data-slot="input"
			className={ cn(
				'file:text-foreground placeholder:text-muted-/50 placeholder:text-sm selection:bg-primary selection:text-primary-foreground border border-brand-grey bg-white flex h-14 w-full min-w-0 rounded-md  px-3 py-1  text-base transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
				'focus-visible:border-ring focus-visible:ring-ring/20 focus-visible:ring-[3px]',
				'aria-invalid:ring-destructive/20 aria-invalid:border-destructive',
				className
			) }
			{ ...props }
		/>
	);
}

export { Input };
