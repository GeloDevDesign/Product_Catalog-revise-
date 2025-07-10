import Swal from "sweetalert2";
import 'sweetalert2/src/sweetalert2.scss'

export function useToastAlert() {
  const toastAlert = (title, icon = "warning") => {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true,
    });

    Toast.fire({
      icon,
      title,
    });
  };

  return { toastAlert };
}