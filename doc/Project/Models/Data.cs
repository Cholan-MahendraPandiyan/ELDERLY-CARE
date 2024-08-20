using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

namespace Project.Models
{
    public class AppointmentSlot
    {
        public int Id { get; set; }
        public DateTime Start { get; set; }
        public DateTime End { get; set; }

        [JsonIgnore()]
        public Doctor Doctor { get; set; }

        [JsonPropertyName("text")]
        public string? PatientName {  set; get; }

        [JsonPropertyName("patient")]
        public string? PatientId { set; get; }

        public string Status { get; set; } = "free";

        [NotMapped]
        public int Resource { get { return Doctor.Id; } }

        [NotMapped]
        public string DoctorName { get { return Doctor.Name; } }

    }

    public class Doctor
    {
        public int Id { get; set; }
        public string Name { get; set; }
    }

    public class DoctorDbContext : DbContext
    {
        public DbSet<AppointmentSlot> Appointments { get; set; }
        public DbSet<Doctor> Doctors { get; set; }

        public DoctorDbContext(DbContextOptions<DoctorDbContext> options) : base(options) { }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Doctor>().HasData(new Doctor { Id = 1, Name = "Doctor 1" });
            modelBuilder.Entity<Doctor>().HasData(new Doctor { Id = 2, Name = "Doctor 2" });
            modelBuilder.Entity<Doctor>().HasData(new Doctor { Id = 3, Name = "Doctor 3" });

        }
    }
}
