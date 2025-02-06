import { HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AlertsService {
  public alertSubject: BehaviorSubject<any> = new BehaviorSubject({
    type: '',
    message: ''
  });
  alert$ = this.alertSubject.asObservable();

  showError(message: string): void {
    this.alertSubject.next({
      type: 'error',
      message: message
    });
  }

  showSuccess(message: string): void {
    this.alertSubject.next({
      type: 'success',
      message: message
    });
  }

  clearMessage(): void {
    this.alertSubject.next({
      type: '',
      message: ''
    });
  }

  public handleError(error: HttpErrorResponse): Observable<any> {
    this.showError(error.error.error || error.error.message);

    return of();
  }
}
